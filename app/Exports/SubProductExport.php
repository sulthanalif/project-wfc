<?php

namespace App\Exports;

use App\Models\SubProduct;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubProductExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return SubProduct::all();
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'unit',
            'price',
            'created_at',
            'updated_at',
        ];
    }
}
