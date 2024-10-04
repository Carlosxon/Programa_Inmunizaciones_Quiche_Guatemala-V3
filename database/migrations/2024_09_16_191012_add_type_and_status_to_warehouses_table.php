<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_type_and_status_to_warehouses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeAndStatusToWarehousesTable extends Migration
{
    public function up()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('type')->nullable(); // Añadir campo tipo de bodega
            $table->string('status')->nullable(); // Añadir campo estado
        });
    }

    public function down()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('status');
        });
    }
}
