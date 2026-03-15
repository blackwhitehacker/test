<?php

namespace App\Traits;

use App\Services\CodeGenerator;
use Illuminate\Database\Eloquent\Builder;

trait BaseAssetModel
{
    /**
     * Boot the trait.
     */
    protected static function bootBaseAssetModel()
    {
        static::creating(function ($model) {
            if (isset(static::$codePrefix) && empty($model->code)) {
                $model->code = CodeGenerator::generate(
                    static::$codePrefix,
                    $model->getTable()
                );
            }
        });

        // Default global scope for "Newest first"
        static::addGlobalScope('newestFirst', function (Builder $builder) {
            $builder->orderBy('id', 'desc'); // Use ID descending as a fallback/additional safety for "Newest"
        });
    }
}
