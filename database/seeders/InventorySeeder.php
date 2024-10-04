<?php
// Database/Seeders/InventorySeeder.php
// Database\Seeders\InventorySeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;

class InventorySeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        $warehouses = Warehouse::all();

        foreach ($warehouses as $warehouse) {
            foreach ($products as $product) {
                Inventory::create([
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => rand(50, 500), // Puedes ajustar el rango de la cantidad como prefieras
                    'acquisition_date' => now(),
                ]);
            }
        }
    }
}
