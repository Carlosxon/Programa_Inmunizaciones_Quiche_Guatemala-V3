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
        // database/migrations/xxxx_xx_xx_create_inventories_table.php

    Schema::create('inventories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
    $table->string('product_name');
    $table->integer('quantity');
    $table->date('acquisition_date');
    $table->timestamps();
      });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
