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
        Schema::create('inventory_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // PNK000001 or PXK000001
            $table->foreignId('request_id')->constrained('inventory_requests');
            $table->foreignId('processor_id')->constrained('users');
            $table->date('process_date');
            $table->text('evaluation_notes')->nullable(); // Đánh giá thiết bị
            $table->json('items')->nullable(); // List of assets and conditions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_receipts');
    }
};
