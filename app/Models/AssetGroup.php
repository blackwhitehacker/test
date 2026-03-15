<?php

namespace App\Models;

use App\Traits\BaseAssetModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetGroup extends Model
{
    use BaseAssetModel;

    protected $fillable = ['code', 'name', 'parent_id', 'tracking_type'];

    public static $codePrefix = 'DM';

    public function parent(): BelongsTo
    {
        return $this->belongsTo(AssetGroup::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(AssetGroup::class, 'parent_id');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'group_id');
    }
}
