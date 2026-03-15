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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // HD000001
            $table->foreignId('partner_id')->constrained('partners');
            $table->foreignId('requisition_id')->nullable()->constrained('purchase_requisitions');
            $table->string('contract_number')->nullable();
            $table->decimal('value', 15, 2)->default(0);
            $table->date('signed_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->integer('warranty_months')->default(0);
            $table->json('files')->nullable();
            $table->enum('status', ['active', 'liquidating', 'liquidated', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
