<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $products = Product::with('packageName')->get();
        $datas = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'package_name' => $product->packageName->name,
                'name' => $product->name,
                'stock' => $product->stock,
                'price' => $product->price,
                'days' => $product->days,
                'total_price' => $product->total_price,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];

        });

        return $datas;
    }

    public function headings(): array
    {
        return [
            'id',
            'package_name',
            'name',
            'stock',
            'price',
            'days',
            'total_price',
            'created_at',
            'updated_at',
        ];
    }


}
