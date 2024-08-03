<?php

namespace App\Exports;

use App\Models\Income;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IncomeExport implements FromView
{
    public $start_date;
    public $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
    public function view(): View
    {
        $query = Income::orderBy('date', 'asc');

        if ($this->start_date && $this->end_date) {
            $startDate = Carbon::parse($this->start_date);
            $endDate = Carbon::parse($this->end_date);
            $query->whereBetween('date', [$startDate, $endDate->addDays(1)]);
        }

        $datas = $query->get();
        return view('cms.admin.export.income', [
            'datas' => $datas
        ]);
    }
}
