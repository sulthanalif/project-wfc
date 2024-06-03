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

    private $datasubs;
    private $stats;

    public function __construct($datasubs, $stats)
    {
        $this->datasubs = $datasubs;
        $this->stats = $stats;
    }

    public function view(): View
    {
        return view('cms.admin.export.requirement', [
            'datasubs' => $this->datasubs,
            'stats' => $this->stats,
        ]);
    }
}
