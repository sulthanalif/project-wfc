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

    public static function generateRandomNumbers($min, $max) {
        // Initialize an empty array to store random numbers
        $randomNumbers = [];

        // Generate 1000 random numbers
        for ($i = 0; $i < 1000; $i++) {
            // Generate a random number between min and max
            $randomNumber = rand($min, $max);

            // Add the random number to the array
            $randomNumbers[] = $randomNumber;
        }

        // Return the array of random numbers
        return $randomNumbers;
    }

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
            $price = self::generateRandomNumbers(3000, 8000)[0];
            $stock = self::generateRandomNumbers(100, 300)[0];
            $days = self::generateRandomNumbers(300, 400)[0];
            $package = Package::create($data);

            $product1 = Product::create([
                'name' => 'Product ' . $package->name . ' 1',
                'stock' => $stock,
                'price' => $price,
                'days' => $days,
                'total_price' => $price * $days
            ]);

            ProductDetail::create([
                'product_id' => $product1->id,
                'description' => 'ini adalah product '. $package->name,
                'image' => 'image.jpg'
            ]);

            $product2 = Product::create([
                'name' => 'Product ' . $package->name . ' 2',
                'stock' => $stock,
                'price' => $price,
                'days' => $days,
                'total_price' => $price * $days
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
