<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductPackage;
use App\Models\ProductSubProduct;
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
            $days = self::generateRandomNumbers(300, 400)[0];
            // $package = Package::create($data);
            $unit = "Pcs";

            $product1 = Product::create([
                // 'package_id' => $package1->id,
                'name' => 'Product ' . $package1->name . ' 1',
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
                    'name' => 'Uang Hampers 500rb',
                    'unit' => 'Rupiah',
                    'price' => '500000',
                ],
                [
                    'name' => 'Lidah Kucing Chashfood',
                    'unit' => 'Gram',
                    'price' => '25000',

                ],
                [
                    'name' => 'Thumbprint by chashfood',
                    'unit' => 'Gram',
                    'price' => '25000',

                ],
            ];
            foreach ($dataSubs1 as $data) {
                $subProduct1 = SubProduct::create($data);

                ProductSubProduct::create([
                    'product_id' => $product1->id,
                    'sub_product_id' => $subProduct1->id,
                    'amount' => 1
                ]);
            }

            $product2 = Product::create([
                // 'package_id' => $package1->id,
                'name' => 'Product ' . $package1->name . ' 2',
                'unit' => $unit,
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
                    'name' => 'Uang Hampers',
                    'unit' => 'Rupiah',
                    'price' => '100000',

                ],
                [
                    'name' => 'Chasfood',
                    'unit' => 'Pcs',
                    'price' => '1000',

                ],
                [
                    'name' => 'Box hampers',
                    'unit' => 'Pcs',
                    'price' => '1567',

                ],
            ];

            foreach ($dataSubs2 as $data) {
                $subProduct2 = SubProduct::create($data);

                ProductSubProduct::create([
                    'product_id' => $product2->id,
                    'sub_product_id' => $subProduct2->id,
                    'amount' => 1
                ]);
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
