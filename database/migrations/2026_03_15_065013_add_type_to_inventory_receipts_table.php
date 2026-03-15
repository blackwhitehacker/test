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
        Schema::table('inventory_receipts', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('type')->default('inbound')->after('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_receipts', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
