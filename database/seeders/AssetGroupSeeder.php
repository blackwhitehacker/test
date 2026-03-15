<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssetGroup;

class AssetGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AssetGroup::firstOrCreate(
            ['name' => 'Công cụ dụng cụ'],
            ['tracking_type' => 'quantity', 'code' => 'CCDC']
        );

        AssetGroup::firstOrCreate(
            ['name' => 'Thiết bị văn phòng'],
            ['tracking_type' => 'serialized', 'code' => 'TBVP']
        );
    }
}
