<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cibaba1 = Product::create([
            'name' => 'Kasur',
            'stock' => 500,
            'price' => 3000,
            'days' => 360
        ]);

        ProductDetail::create([
            'product_id' => $cibaba1->id,
            'description' => 'ini adalah kasur',
            'image' => 'image.jpg'
        ]);

        $cibaba2 = Product::create([
            'name' => 'lemari makan',
            'stock' => 500,
            'price' => 5000,
            'days' => 360
        ]);

        ProductDetail::create([
            'product_id' => $cibaba2->id,
            'description' => 'ini adalah lemari makan',
            'image' => 'image.jpg'
        ]);

        $phl1 = Product::create([
            'name' => 'Paket Hampers Lebaran 1',
            'stock' => 500,
            'price' => 3000,
            'days' => 325
        ]);

        ProductDetail::create([
            'product_id' => $phl1->id,
            'description' => '<p>item paket :</p><ul><li>Uang hampers Rp. 500.000</li><li>Lidah kucing</li><li>dll</li><li>dll</li><li>dll</li></ul>',
            'image' => 'image.jpg'
        ]);

        $phl2 = Product::create([
            'name' => 'Paket Hampers Lebaran 2',
            'stock' => 500,
            'price' => 3000,
            'days' => 325
        ]);

        ProductDetail::create([
            'product_id' => $phl2->id,
            'description' => '<p>item paket :</p><ul><li>Uang hampers Rp. 300.000</li><li>Lidah kucing</li><li>dll</li><li>dll</li><li>dll</li></ul>',
            'image' => 'image.jpg'
        ]);
    }
}
