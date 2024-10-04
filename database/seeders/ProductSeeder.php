<?php

// Database/Seeders/ProductSeeder.php
// Database\Seeders\ProductSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['name' => 'BCG', 'description' => 'Vacuna contra la tuberculosis', 'price' => 20.00, 'stock' => 1000, 'category_id' => 1, 'brand_id' => 1, 'unit_id' => 1, 'warehouse_id' => 1, 'barcode' => '1234567890'],
            ['name' => 'Hepatitis B', 'description' => 'Vacuna contra la Hepatitis B', 'price' => 25.00, 'stock' => 1000, 'category_id' => 1, 'brand_id' => 1, 'unit_id' => 1, 'warehouse_id' => 1, 'barcode' => '1234567891'],
            ['name' => 'Pentavalente', 'description' => 'Difteria, tosferina, tétanos, hepatitis B y haemophilus influenzae tipo B', 'price' => 35.00, 'stock' => 1000, 'category_id' => 1, 'brand_id' => 1, 'unit_id' => 1, 'warehouse_id' => 1, 'barcode' => '1234567892'],
            ['name' => 'Polio (IPV)', 'description' => 'Vacuna inactivada contra la poliomielitis', 'price' => 15.00, 'stock' => 1000, 'category_id' => 1, 'brand_id' => 1, 'unit_id' => 1, 'warehouse_id' => 1, 'barcode' => '1234567893'],
            ['name' => 'Rotavirus', 'description' => 'Vacuna contra el rotavirus', 'price' => 30.00, 'stock' => 1000, 'category_id' => 1, 'brand_id' => 1, 'unit_id' => 1, 'warehouse_id' => 1, 'barcode' => '1234567894'],
            ['name' => 'Neumococo', 'description' => 'Vacuna contra el neumococo', 'price' => 40.00, 'stock' => 1000, 'category_id' => 1, 'brand_id' => 1, 'unit_id' => 1, 'warehouse_id' => 1, 'barcode' => '1234567895'],
            ['name' => 'SRP', 'description' => 'Vacuna triple viral (sarampión, rubéola y parotiditis)', 'price' => 28.00, 'stock' => 1000, 'category_id' => 1, 'brand_id' => 1, 'unit_id' => 1, 'warehouse_id' => 1, 'barcode' => '1234567896'],
            ['name' => 'Varicela', 'description' => 'Vacuna contra la varicela', 'price' => 50.00, 'stock' => 1000, 'category_id' => 1, 'brand_id' => 1, 'unit_id' => 1, 'warehouse_id' => 1, 'barcode' => '1234567897'],
            ['name' => 'Influenza', 'description' => 'Vacuna contra la influenza estacional', 'price' => 22.00, 'stock' => 1000, 'category_id' => 1, 'brand_id' => 1, 'unit_id' => 1, 'warehouse_id' => 1, 'barcode' => '1234567898'],
            ['name' => 'COVID-19 (Pfizer)', 'description' => 'Vacuna contra el COVID-19', 'price' => 100.00, 'stock' => 1000, 'category_id' => 1, 'brand_id' => 1, 'unit_id' => 1, 'warehouse_id' => 1, 'barcode' => '1234567899'],
            ['name' => 'COVID-19 (Moderna)', 'description' => 'Vacuna contra el COVID-19', 'price' => 110.00, 'stock' => 1000, 'category_id' => 1, 'brand_id' => 1, 'unit_id' => 1, 'warehouse_id' => 1, 'barcode' => '1234567810'],
            // Agrega todas las demás vacunas que necesites
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
