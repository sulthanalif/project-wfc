<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class ReportProductDetailExport implements FromView
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
        return view('cms.admin.export.product-detail', [
            'datas' => $this->datas,
            'stats' => $this->stats,
        ]);
    }
}
