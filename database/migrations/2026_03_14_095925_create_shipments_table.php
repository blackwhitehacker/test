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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // LH000001
            $table->foreignId('contract_id')->constrained('contracts');
            $table->date('delivery_date')->nullable();
            $table->enum('status', ['pending', 'delivered', 'checked', 'partially_received', 'received'])->default('pending');
            $table->foreignId('receiver_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
