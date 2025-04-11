<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Category::create([
            'name' => 'Luxury Watches',
            'slug' => 'luxury-watches',
            'description' => 'High-end luxury timepieces'
        ]);

        Category::create([
            'name' => 'Sports Watches',
            'slug' => 'sports-watches',
            'description' => 'Durable watches for active lifestyles'
        ]);
    }
}