<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inventory_requests', function (Blueprint $table) {
            $table->decimal('recovery_value', 15, 2)->nullable()->after('repair_notes');
            $table->text('liquidation_notes')->nullable()->after('recovery_value');
        });
    }

    public function down()
    {
        Schema::table('inventory_requests', function (Blueprint $table) {
            $table->dropColumn(['recovery_value', 'liquidation_notes']);
        });
    }
};
