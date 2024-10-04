<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {   
        //
        Stock::create([
            'product_id' => 1,
            'warehouse_id' => 1,
            'quantity' => 100,
        ]);
    
        Stock::create([
            'product_id' => 3,
            'warehouse_id' => 1,
            'quantity' => 50,
        ]);
    
        // Añade más registros según sea necesario
    }
}
