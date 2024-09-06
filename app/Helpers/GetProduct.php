<?php

namespace App\Helpers;

use App\Models\Product;

class GetProduct
{
    public static function detail(string $name)
    {
        $result = Product::where('name', 'like', '%' . $name . '%')->get();
        return $result;
    }
}
