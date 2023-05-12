<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Inventory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        Product::truncate();
        Inventory::truncate();

        Product::factory(10)->create()->each(function ($product) {
            $product->inventory()->save(Inventory::factory()->make());
        });
    }
}
