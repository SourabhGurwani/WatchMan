<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing products
        Product::truncate();

        // Ensure categories exist
        if (Category::count() === 0) {
            $this->call(CategorySeeder::class);
        }

        $products = [
            [
                'name' => 'Rolex Submariner',
                'slug' => 'rolex-submariner',
                'category_id' => 1, // Must match existing category
                'price' => 8999.99,
                'stock' => 5,
                'description' => 'Iconic luxury diving watch with 300m water resistance',
                'images' => json_encode(['rolex-submariner-black.jpg'])
            ],
            [
                'name' => 'Omega Speedmaster',
                'slug' => 'omega-speedmaster',
                'category_id' => 1, // Luxury Watches
                'price' => 5999.99,
                'stock' => 3,
                'description' => 'The famous Moonwatch with chronograph function',
                'images' => json_encode(['omega-speedmaster.jpg'])
            ],
            [
                'name' => 'Casio G-Shock',
                'slug' => 'casio-g-shock',
                'category_id' => 2, // Sports Watches
                'price' => 199.99,
                'stock' => 20,
                'description' => 'Rugged shock-resistant sports watch',
                'images' => json_encode(['gshock-black.jpg'])
            ],
            [
                'name' => 'Timex Expedition',
                'slug' => 'timex-expedition',
                'category_id' => 2, // Sports Watches
                'price' => 89.99,
                'stock' => 15,
                'description' => 'Durable outdoor watch with Indiglo backlight',
                'images' => json_encode(['timex-expedition.jpg'])
            ]
        ];

        foreach ($products as $product) {
            try {
                Product::updateOrCreate(
                    ['slug' => $product['slug']],
                    $product
                );
            } catch (\Exception $e) {
                $this->command->error("Failed to create product {$product['name']}: ".$e->getMessage());
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Successfully seeded '.count($products).' products.');
    }
}