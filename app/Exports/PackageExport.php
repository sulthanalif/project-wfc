<?php

namespace App\Exports;

use App\Models\Package;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PackageExport implements FromCollection, WithHeadings
{
    public $packages;
    public function __construct($packages)
    {
        $this->packages = $packages;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $datas = $this->packages->map(function ($package) {
           return [
               'id' => $package->id,
               'name' => $package->name,
               'periode' => $package->period->description,
               'description' => $package->description,
            //    'image' => $package->image,
           ];
        });

        return $datas;
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'periode',
            'description',
            // 'image',
            // 'created_at',
            // 'updated_at',
        ];
    }
}
