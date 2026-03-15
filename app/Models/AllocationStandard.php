<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllocationStandard extends Model
{
    protected $fillable = [
        'asset_group_id',
        'target_type',
        'target_name',
        'limit_quantity',
        'notes',
    ];

    public function assetGroup(): BelongsTo
    {
        return $this->belongsTo(AssetGroup::class, 'asset_group_id');
    }
}
