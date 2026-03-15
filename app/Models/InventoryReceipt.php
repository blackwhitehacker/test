<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryReceipt extends Model
{
    use LogsActivity;

    public static $codePrefix = 'PNK'; // Default

    protected $fillable = [
        'code',
        'type',
        'status',
        'request_id',
        'processor_id',
        'process_date',
        'evaluation_notes',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
        'process_date' => 'date',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $request = InventoryRequest::find($model->request_id);
            $prefix = ($request && $request->type === 'inbound') ? 'PNK' : 'PXK';
            $model->code = \App\Services\CodeGenerator::generate($prefix, $model->getTable());
        });
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(InventoryRequest::class, 'request_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processor_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'model_id')
            ->where('model_type', get_class($this))
            ->latest();
    }
}
