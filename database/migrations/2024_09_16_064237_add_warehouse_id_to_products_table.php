<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWarehouseIdToProductsTable extends Migration
{
    /**
     * Ejecuta las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Agregar la columna warehouse_id
            $table->unsignedBigInteger('warehouse_id')->after('unit_id')->nullable();

            // Agregar la clave foránea (si la tabla warehouses existe)
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null');
        });
    }

    /**
     * Reversa las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Eliminar la clave foránea
            $table->dropForeign(['warehouse_id']);

            // Eliminar la columna warehouse_id
            $table->dropColumn('warehouse_id');
        });
    }
}
