<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inventory_requests', function (Blueprint $table) {
            $table->string('repair_status')->nullable()->after('status'); // repairing, completed, unfixable
            $table->decimal('repair_cost', 15, 2)->nullable()->after('repair_status');
            $table->text('repair_notes')->nullable()->after('repair_cost');
        });
    }

    public function down()
    {
        Schema::table('inventory_requests', function (Blueprint $table) {
            $table->dropColumn(['repair_status', 'repair_cost', 'repair_notes']);
        });
    }
};
