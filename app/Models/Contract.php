<?php

namespace App\Models;

use App\Traits\BaseAssetModel;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    use BaseAssetModel, LogsActivity;

    protected $fillable = [
        'code',
        'partner_id',
        'requisition_id',
        'contract_number',
        'value',
        'signed_date',
        'expiration_date',
        'warranty_months',
        'files',
        'items',
        'status',
    ];

    protected $casts = [
        'files' => 'array',
        'items' => 'array',
        'signed_date' => 'date',
        'expiration_date' => 'date',
    ];

    public static $codePrefix = 'HD';

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'model_id')
            ->where(function($query) {
                $query->where('model_type', get_class($this));
            })
            ->latest();
    }
}
