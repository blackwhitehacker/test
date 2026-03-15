<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asset_groups', function (Blueprint $table) {
            $table->string('tracking_type')->default('serialized')->after('parent_id'); // 'quantity' or 'serialized'
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_groups', function (Blueprint $table) {
            $table->dropColumn('tracking_type');
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
