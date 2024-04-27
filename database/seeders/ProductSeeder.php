<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product1 = Product::create([
            'name' => 'Produk 1',
            'stock' => 500,
            'price' => 2000,
            'days' => 360
        ]);

        ProductDetail::create([
            'product_id' => $product1->id,
            'description' => 'ini adalah product 1',
            'image' => 'image.jpg'
        ]);

        $product2 = Product::create([
            'name' => 'Produk 2',
            'stock' => 500,
            'price' => 5000,
            'days' => 360
        ]);

        ProductDetail::create([
            'product_id' => $product2->id,
            'description' => 'ini adalah product 1',
            'image' => 'image.jpg'
        ]);
    }
}
