<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Aquí puedes llamar a otros seeders si los tienes
        // $this->call(OtroSeeder::class);

        $this->call([
            ProductSeeder::class,
            InventorySeeder::class,
        ]);
    }
}