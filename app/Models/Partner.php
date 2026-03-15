<?php

namespace App\Models;

use App\Traits\BaseAssetModel;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use BaseAssetModel;

    protected $fillable = [
        'code',
        'name',
        'tax_code',
        'address',
        'phone',
        'email',
        'contact_person',
    ];

    public static $codePrefix = 'NCC';
}
