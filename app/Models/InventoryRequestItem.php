<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryRequestItem extends Model
{
    protected $fillable = [
        'inventory_request_id',
        'asset_id',
        'name',
        'quantity',
        'specification',
        'price',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(InventoryRequest::class, 'inventory_request_id');
    }
}
