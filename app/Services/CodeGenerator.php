<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CodeGenerator
{
    /**
     * Generate a sequential code based on prefix and table.
     * Format: PREFIX + 6 digits (e.g., TS000001)
     */
    public static function generate(string $prefix, string $table, string $column = 'code'): string
    {
        $lastRecord = DB::table($table)
            ->where($column, 'like', "{$prefix}%")
            ->orderBy($column, 'desc')
            ->first();

        if (!$lastRecord) {
            return $prefix . str_pad('1', 6, '0', STR_PAD_LEFT);
        }

        $lastCode = $lastRecord->$column;
        $lastNumber = (int) substr($lastCode, strlen($prefix));
        $nextNumber = $lastNumber + 1;

        return $prefix . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
