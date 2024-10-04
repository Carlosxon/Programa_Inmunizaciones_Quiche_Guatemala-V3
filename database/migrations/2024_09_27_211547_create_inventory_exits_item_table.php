<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryExitsItemTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_exit_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_exit_id');
            $table->unsignedBigInteger('inventory_item_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('inventory_exit_id')->references('id')->on('inventory_exits')->onDelete('cascade');
            $table->foreign('inventory_item_id')->references('id')->on('inventories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_exits_items');
    }
};