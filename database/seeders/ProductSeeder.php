<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Kopi ABC',
                'price' => 2500,
                'stock' => 100,
                'photo' => 'example.png',
            ],
            [
                'name' => 'Kopi Luwak',
                'price' => 3500,
                'stock' => 75,
                'photo' => 'example.png',
            ],
            [
                'name' => 'Nescafe',
                'price' => 1500,
                'stock' => 150,
                'photo' => 'example.png',
            ]
        ];
        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
