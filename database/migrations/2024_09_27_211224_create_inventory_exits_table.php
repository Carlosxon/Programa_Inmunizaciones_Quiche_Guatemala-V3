<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryExitsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_exits', function (Blueprint $table) {
            $table->id();
            $table->string('exit_number')->unique();
            $table->date('exit_date');
            $table->unsignedBigInteger('user_id');
            $table->string('destination');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_exits'); // Cambiar 'inventory_exit' a 'inventory_exits'
    }
};
