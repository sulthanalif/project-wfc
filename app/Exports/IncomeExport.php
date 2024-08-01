<?php

namespace App\Exports;

use App\Models\Income;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IncomeExport implements FromView
{
    public function view(): View
    {
        $datas = Income::orderBy('date', 'asc')->get();
        return view('cms.admin.export.income', [
            'datas' => $datas
        ]);
    }
}
