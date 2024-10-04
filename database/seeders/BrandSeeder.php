<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Brand::create(['name' => 'Caplin Point']);
        Brand::create(['name' => 'Infasa']);
        // Añade más marcas según necesites
    }
}
