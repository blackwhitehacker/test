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
        Schema::table('inventory_requests', function (Blueprint $table) {
            $table->string('target_type')->nullable()->after('source_type'); // individual, department, center
            $table->string('target_name')->nullable()->after('target_type'); // User name, Dept name, or Center name
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_requests', function (Blueprint $table) {
            $table->dropColumn(['target_type', 'target_name']);
        });
    }
};
