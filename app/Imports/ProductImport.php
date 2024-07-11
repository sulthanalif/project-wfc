<?php

namespace App\Imports;

use App\Models\Package;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductPackage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $package = Package::where(DB::raw('LOWER(name)'), 'like', '%' . strtolower($row['package_name']) . '%')->first();

        $product = new Product([
            'name' => $row['name'],
            'price' => $row['price'],
            'days'  => $row['days'],
            'total_price' => $row['total_price']
        ]);
        $product->save();

        $detail = new ProductDetail([
            'product_id' => $product->id,
            'description' => $row['description'],
        ]);

        $detail->save();

        if ($package){
            return new ProductPackage([
                'product_id' => $product->id,
                'package_id' => $package->id
            ]);
        } else {
            return $product;
        }




    }
}
