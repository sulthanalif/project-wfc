<?php

namespace App\Exports;

// use Illuminate\View\View;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
// use Maatwebsite\Excel\Concerns\FromCollection;

class ReportRequirementExport implements FromView
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
        return view('cms.admin.export.requirement', [
            'payments' => $this->datas,
            'stats' => $this->stats,
        ]);
    }
}
