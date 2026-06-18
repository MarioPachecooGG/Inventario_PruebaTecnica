<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // 1. CATEGORÍAS (3)
        // =========================
        $categories = [
            Category::create([
                'name' => 'Electrónica',
                'description' => 'Productos electrónicos'
            ]),
            Category::create([
                'name' => 'Oficina',
                'description' => 'Artículos de oficina'
            ]),
            Category::create([
                'name' => 'Hogar',
                'description' => 'Productos para el hogar'
            ]),
        ];

        // =========================
        // 2. PRODUCTOS (10)
        // =========================
        $products = [];

        for ($i = 1; $i <= 10; $i++) {
            $products[] = Product::create([
                'category_id' => $categories[array_rand($categories)]->id,
                'name' => "Producto $i",
                'sku' => "SKU-$i",
                'stock' => rand(10, 100),
                'price' => rand(50, 500)
            ]);
        }

        // =========================
        // 3. VENTAS (20)
        // =========================
        for ($i = 1; $i <= 20; $i++) {

            $product = $products[array_rand($products)];

            $quantity = rand(1, 5);

            Sale::create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'total_price' => $product->price * $quantity,
                'sale_date' => now()->startOfMonth()->addDays(rand(0, 27))
            ]);
        }
    }
}