<?php

namespace App\Models;

use App\Traits\BaseAssetModel;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    use BaseAssetModel, LogsActivity;

    protected $fillable = [
        'code',
        'contract_id',
        'delivery_date',
        'status',
        'receiver_id',
        'receiver_name',
        'items',
        'note',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'items' => 'array',
    ];

    public static $codePrefix = 'LH';

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
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
