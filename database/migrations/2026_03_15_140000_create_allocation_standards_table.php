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
        Schema::create('allocation_standards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_group_id')->constrained()->onDelete('cascade');
            $table->string('target_type'); // individual, department, center
            $table->string('target_name'); // e.g., 'Trưởng phòng', 'Phòng Kế toán', 'Phòng kỹ thuật'
            $table->integer('limit_quantity')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocation_standards');
    }
};
