<?php

namespace App\Exports;

use App\Models\Package;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PackageExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Package::all();
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'description',
            'image',
            'created_at',
            'updated_at',
        ];
    }
}
