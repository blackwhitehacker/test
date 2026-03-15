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
            $table->string('assessment_status')->nullable()->after('status'); // pending, safe, damaged, broken
            $table->text('assessment_notes')->nullable()->after('assessment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_requests', function (Blueprint $table) {
            //
        });
    }
};
