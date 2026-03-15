<?php

namespace App\Models;

use App\Traits\BaseAssetModel;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseRequisition extends Model
{
    use BaseAssetModel, LogsActivity;

    protected $fillable = [
        'code',
        'requester_id',
        'partner_id',
        'department',
        'title',
        'description',
        'items',
        'estimated_cost',
        'attachments',
        'status',
        'rejection_reason',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    protected $casts = [
        'attachments' => 'array',
        'items' => 'array',
    ];

    public static $codePrefix = 'TT';

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'requisition_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'model_id')
            ->where(function($q) {
                $q->where('model_type', get_class($this));
            })
            ->latest();
    }
}
