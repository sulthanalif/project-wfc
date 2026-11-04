<?php

namespace App\Exports;

use App\Models\Loan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LoanExport implements FromView
{
    public function view(): View
    {
        $loans = Loan::with(['loanPayments', 'bankOwner'])->latest()->get();

        return view('cms.admin.export.loan', [
            'loans' => $loans,
        ]);
    }
}
