<?php

namespace App\Imports;

use App\Models\SubProduct;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubProductImport implements ToModel, WithHeadingRow
{
   public function model(array $row)
   {
        $subProduct = new SubProduct([
            'name' => $row['name'],
            'unit' => $row['unit'],
            'price' => $row['price'],
        ]);

        $subProduct->save();

        return $subProduct;
   }
}
