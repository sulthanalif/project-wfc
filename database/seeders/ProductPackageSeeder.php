<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductPackage;
use App\Models\SubProduct;
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
        $package1 = Package::create([
            'name' => 'PHL',
            'description' => 'ini Deskripsi PHL',
        ]);

            $price = self::generateRandomNumbers(3000, 8000)[0];
            $stock = self::generateRandomNumbers(100, 300)[0];
            $days = self::generateRandomNumbers(300, 400)[0];
            // $package = Package::create($data);
            $unit = "Pcs";

            $product1 = Product::create([
                // 'package_id' => $package1->id,
                'name' => 'Product ' . $package1->name . ' 1',
                'stock' => $stock,
                'unit' => $unit,
                'price' => $price,
                'days' => $days,
                'total_price' => $price * $days
            ]);

            ProductDetail::create([
                'product_id' => $product1->id,
                'description' => 'ini adalah product '. $package1->name,
                'image' => 'image.jpg'
            ]);

            //subproduct
            $dataSubs1 = [
                [
                    'product_id' => $product1->id,
                    'name' => 'Uang Hampers 500rb',
                    'unit' => 'Rupiah',
                    'amount' => '500000',
                    'price' => '500000',
                ],
                [
                    'product_id' => $product1->id,
                    'name' => 'Lidah Kucing Chashfood',
                    'unit' => 'Gram',
                    'amount' => '250',

                ],
                [
                    'product_id' => $product1->id,
                    'name' => 'Thumbprint by chashfood',
                    'unit' => 'Gram',
                    'amount' => '250',

                ],
            ];
            foreach ($dataSubs1 as $data) {
                SubProduct::create($data);
            }

            $product2 = Product::create([
                // 'package_id' => $package1->id,
                'name' => 'Product ' . $package1->name . ' 2',
                'unit' => $unit,
                'stock' => $stock,
                'price' => $price,
                'days' => $days,
                'total_price' => $price * $days
            ]);

            ProductDetail::create([
                'product_id' => $product2->id,
                'description' => 'ini adalah product '. $package1->name,
                'image' => 'image.jpg'
            ]);

            $dataSubs2 = [
                [
                    'product_id' => $product2->id,
                    'name' => 'Uang Hampers',
                    'unit' => 'Rupiah',
                    'amount' => '100000',

                ],
                [
                    'product_id' => $product2->id,
                    'name' => 'Chasfood',
                    'unit' => 'Pcs',
                    'amount' => '1',

                ],
                [
                    'product_id' => $product2->id,
                    'name' => 'Box hampers',
                    'unit' => 'Pcs',
                    'amount' => '1',

                ],
            ];

            foreach ($dataSubs2 as $data) {
                SubProduct::create($data);
            }

            ProductPackage::create([
                'product_id' => $product1->id,
                'package_id' => $package1->id
            ]);

            ProductPackage::create([
                'product_id' => $product2->id,
                'package_id' => $package1->id
            ]);


    }
}
