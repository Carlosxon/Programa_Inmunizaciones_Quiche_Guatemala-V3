<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('stocks', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('product_id');
        $table->unsignedBigInteger('warehouse_id');
        $table->integer('quantity');
        $table->timestamps();

        // Relación con la tabla productos
        $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

        // Relación con la tabla almacenes (bodegas)
        $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');

        // Evitar duplicados de stock por producto y almacén
        $table->unique(['product_id', 'warehouse_id']);
    });
}

}
