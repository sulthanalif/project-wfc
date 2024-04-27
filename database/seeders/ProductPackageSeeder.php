<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductPackage;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => 'Cibaba',
                'description' => 'ini Deskripsi Cibaba',

            ],
            [
                'name' => 'Cookies',
                'description' => 'ini Deskripsi Cookies',

            ],
            [
                'name' => 'PHL',
                'description' => 'ini Deskripsi PHL',

            ],
            [
                'name' => 'TaBaBa',
                'description' => 'ini Deskripsi TaBaBa',

            ],
            [
                'name' => 'THR Smart Home',
                'description' => 'ini Deskripsi THR Smart Home',

            ],
            [
                'name' => 'TMR',
                'description' => 'ini Deskripsi TMR',

            ],
        ];


        foreach ($datas as $data) {
            $package = Package::create($data);

            $product1 = Product::create([
                'name' => 'Product ' . $package->name . ' 1',
                'stock' => 500,
                'price' => 3000,
                'days' => 360
            ]);

            ProductDetail::create([
                'product_id' => $product1->id,
                'description' => 'ini adalah product '. $package->name,
                'image' => 'image.jpg'
            ]);

            $product2 = Product::create([
                'name' => 'Product ' . $package->name . ' 1',
                'stock' => 500,
                'price' => 3000,
                'days' => 360
            ]);

            ProductDetail::create([
                'product_id' => $product2->id,
                'description' => 'ini adalah product '. $package->name,
                'image' => 'image.jpg'
            ]);

            ProductPackage::create([
                'product_id' => $product1->id,
                'package_id' => $package->id
            ]);

            ProductPackage::create([
                'product_id' => $product2->id,
                'package_id' => $package->id
            ]);
        }
    }
}
