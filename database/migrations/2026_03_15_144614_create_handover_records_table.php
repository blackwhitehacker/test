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
        Schema::create('handover_records', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // BBBG000001
            $table->foreignId('inventory_request_id')->constrained('inventory_requests')->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users');
            $table->foreignId('receiver_id')->nullable()->constrained('users');
            $table->string('receiver_name');
            $table->string('receiver_department')->nullable();
            $table->string('receiver_position')->nullable();
            $table->date('handover_date');
            $table->enum('status', ['draft', 'signed', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('handover_records');
    }
};
