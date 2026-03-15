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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // TS000001
            $table->string('name');
            $table->foreignId('group_id')->constrained('asset_groups');
            $table->foreignId('partner_id')->nullable()->constrained('partners');
            
            // Specifications
            $table->string('serial_number')->nullable();
            $table->string('model')->nullable();
            $table->string('specs')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            
            // Financial & Depreciation
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('recovery_value', 15, 2)->default(0); // Giá trị thu hồi
            $table->integer('usage_months')->default(0); // Thời gian sử dụng (tháng)
            $table->decimal('monthly_depreciation', 15, 2)->default(0); // Tự động tính
            
            // Status & Location
            $table->enum('status', ['inventory', 'in_use', 'repairing', 'liquidating', 'liquidated'])->default('inventory');
            $table->foreignId('current_user_id')->nullable()->constrained('users');
            $table->string('location')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
