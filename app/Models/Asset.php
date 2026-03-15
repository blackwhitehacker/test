<?php

namespace App\Models;

use App\Traits\BaseAssetModel;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use BaseAssetModel, LogsActivity;

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
    ];

    protected $fillable = [
        'code',
        'name',
        'group_id',
        'partner_id',
        'serial_number',
        'model',
        'specs',
        'purchase_date',
        'warranty_expiry',
        'purchase_price',
        'recovery_value',
        'usage_months',
        'monthly_depreciation',
        'status',
        'current_user_id',
        'assigned_department',
        'assigned_center',
        'location',
        'quantity',
    ];

    public static $codePrefix = 'TS';

    protected static function boot()
    {
        parent::boot();

        // Auto-calculate depreciation before saving
        static::saving(function ($asset) {
            if ($asset->usage_months > 0) {
                $asset->monthly_depreciation = ($asset->purchase_price - $asset->recovery_value) / $asset->usage_months;
            } else {
                $asset->monthly_depreciation = 0;
            }
        });
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(AssetGroup::class, 'group_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_user_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'model_id')
            ->where(function($q) {
                $q->where('model_type', get_class($this));
            })
            ->latest();
    }

    /**
     * Get number of months elapsed since purchase
     */
    public function getMonthsElapsedAttribute(): int
    {
        if (!$this->purchase_date) return 0;
        
        $purchaseDate = \Carbon\Carbon::parse($this->purchase_date);
        $now = \Carbon\Carbon::now();
        
        if ($now->lessThan($purchaseDate)) return 0;
        
        return $purchaseDate->diffInMonths($now);
    }

    /**
     * Get remaining value of the asset
     */
    public function getRemainingValueAttribute(): float
    {
        $elapsed = $this->months_elapsed;
        
        if ($elapsed >= $this->usage_months) {
            return $this->recovery_value;
        }

        $totalDepriciable = $this->purchase_price - $this->recovery_value;
        $accruedDepreciation = $this->monthly_depreciation * $elapsed;
        
        return max($this->recovery_value, $this->purchase_price - $accruedDepreciation);
    }

    /**
     * Get remaining months of depreciation
     */
    public function getRemainingMonthsAttribute(): int
    {
        $remaining = $this->usage_months - $this->months_elapsed;
        return max(0, $remaining);
    }

    /**
     * Scope for assets with < 3 months of depreciation remaining
     */
    public function scopeDepreciationWarning($query)
    {
        $driver = $query->getConnection()->getDriverName();

        $query->where('status', '!=', 'liquidated')
              ->where('usage_months', '>', 0);

        if ($driver === 'sqlite') {
            // SQLite equivalent for finding assets near end of depreciation
            return $query->whereRaw("
                (usage_months - (
                    (strftime('%Y', 'now') - strftime('%Y', purchase_date)) * 12 + 
                    (strftime('%m', 'now') - strftime('%m', purchase_date))
                )) < 3
            ");
        }

        // MySQL version
        return $query->whereRaw('(usage_months - PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM NOW()), EXTRACT(YEAR_MONTH FROM purchase_date))) < 3');
    }

    /**
     * Check if depreciation is almost over (< 3 months)
     */
    public function isDepreciationEndingSoon(): bool
    {
        if ($this->usage_months <= 0 || !$this->purchase_date) return false;
        return $this->remaining_months > 0 && $this->remaining_months < 3;
    }
}
