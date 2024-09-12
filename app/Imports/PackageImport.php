<?php

namespace App\Imports;

use App\Models\Period;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PackageImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $period = Period::where(DB::raw('LOWER(description)'), 'like', '%' . strtolower($row['period']) . '%')->first();
        return new Package([
            'name' => $row['name'],
            'description' => $row['description'],
            'period_id' => $period->id,
        ]);
    }
}
