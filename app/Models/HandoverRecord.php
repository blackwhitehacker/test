<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\InventoryRequest;

class HandoverRecord extends Model
{
    protected $fillable = [
        'code',
        'type',
        'inventory_request_id',
        'creator_id',
        'receiver_id',
        'receiver_name',
        'receiver_department',
        'receiver_position',
        'handover_date',
        'status',
        'signed_at',
        'notes',
    ];

    public function inventoryRequest(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InventoryRequest::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function receiver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
