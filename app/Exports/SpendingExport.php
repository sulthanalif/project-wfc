<?php

namespace App\Exports;

// use App\Models\Spending;

use App\Models\Spending;
use App\Models\SpendingType;
use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class SpendingExport implements FromView
{
    public function view(): View
    {
        $datas = Spending::orderBy('date', 'asc')->get();
        $types = SpendingType::orderBy('created_at', 'asc')->get();
        return view('cms.admin.export.spending', [
            'datas' => $datas,
            'types' => $types
        ]);
    }
}
