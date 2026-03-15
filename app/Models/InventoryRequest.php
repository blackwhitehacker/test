<?php

namespace App\Models;

use App\Traits\BaseAssetModel;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryRequest extends Model
{
    use BaseAssetModel, LogsActivity;

    protected $fillable = [
        'code',
        'type',
        'source_type',
        'requester_id',
        'shipment_id',
        'status',
        'notes',
        'rejection_reason',
        'receiver',
        'target_type',
        'target_name',
        'assessment_status',
        'assessment_notes',
        'repair_status',
        'repair_cost',
        'repair_notes',
        'recovery_value',
        'liquidation_notes',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $prefix = 'YC';
            if ($model->type === 'inbound') {
                $prefix = 'YCNK';
            } elseif ($model->type === 'outbound') {
                $prefix = 'YCXK';
            }

            // Ghi đè prefix chuyên biệt dựa trên source_type
            $sourceType = $model->source_type;
            if ($sourceType === 'allocation') $prefix = 'YCCP';
            elseif ($sourceType === 'repair') $prefix = 'YCSC';
            elseif ($sourceType === 'recall') $prefix = 'YCTH';

            $model->code = \App\Services\CodeGenerator::generate($prefix, $model->getTable());
        });
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(InventoryReceipt::class, 'request_id');
    }

    public function receipt()
    {
        return $this->hasOne(InventoryReceipt::class, 'request_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InventoryRequestItem::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'model_id')
            ->where(function($q) {
                $q->where('model_type', get_class($this));
            })
            ->latest();
    }

    public function handoverRecord(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(HandoverRecord::class, 'inventory_request_id');
    }
}
