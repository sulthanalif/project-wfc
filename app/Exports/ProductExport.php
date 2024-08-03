<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductExport implements FromCollection, WithHeadings
{
    public $period;
    public function __construct($period)
    {
        $this->period = $period;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if ($this->period) {
            $period = \App\Models\Period::find($this->period);
            if ($period) {
                $products = $period->package->product()->with('packageName', 'detail')->get();
            } else {
                $products = Product::with('packageName', 'detail')->get();
            }
        } else {
            $products = Product::with('packageName', 'detail')->get();
        }
        $datas = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'package_name' => $product->packageName->name,
                'name' => $product->name,
                'price' => $product->price,
                'unit' => $product->unit,
                'days' => $product->days,
                'total_price' => $product->total_price,
                'description' =>$product->detail->description,
                'is_safe_point' => $product->is_safe_point ? '1' : '0',
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
            'price',
            'unit',
            'days',
            'total_price',
            'description',
            'is_safe_point',
            'created_at',
            'updated_at',
        ];
    }


}
