<?php

namespace App\Exports;

// use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ReportTotalDepositExport implements FromView
{
    use Exportable;

    private $datas;
    private $stats;

    public function __construct($datas, $stats)
    {
        $this->datas = $datas;
        $this->stats = $stats;
    }

    public function view(): View
    {
        return view('cms.admin.export.total-deposit', [
            'datas' => $this->datas,
            'stats' => $this->stats,
        ]);
    }

}
