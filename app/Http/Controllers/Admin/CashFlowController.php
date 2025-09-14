<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankOwner;
use App\Models\Income;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\Payment;
use App\Models\Spending;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
    public function index()
    {
        $cashFlows = [];

        // Ambil data bank transfer
        $banks = BankOwner::all();
        foreach ($banks as $bank) {
            $income = Income::where('bank_owner_id', $bank->id)
                ->where('method', 'transfer')
                ->sum('amount');
            $loanPayment = LoanPayment::where('method', 'transfer')
                ->where('bank_owner_id', $bank->id)
                ->sum('pay');
            $order = $bank->payments()
                    ->where('status', 'accepted')
                    ->where('bank_owner_id', $bank->id)
                    ->where('method', 'transfer')
                    ->sum('pay');
            $spending = Spending::where('method', 'transfer')
                ->where('bank_owner_id', $bank->id)
                ->sum('amount');
            $loan = Loan::where('method', 'transfer')
                ->where('bank_owner_id', $bank->id)
                ->sum('amount');
            $cashFlows[] = [
                'method' => $bank->name,
                'incomes' => $loanPayment + $income,
                'orders' => $order,
                'spendings' => $spending,
                'loans' => $loan,
                'balance' => ($loanPayment + $income + $order) - ($spending + $loan),
            ];
        }

        // Ambil data tunai
        $incomeCash = Income::where('method', 'Tunai')->sum('amount');
        $loanPaymentCash = LoanPayment::where('method', 'Tunai')->sum('pay');
        $orderCash = Payment::where('status', 'accepted')
            ->where('method', 'Tunai')
            ->sum('pay');
        $spendingCash = Spending::where('method', 'Tunai')->sum('amount');
        $loanCash = Loan::where('method', 'Tunai')->sum('amount');

        $cashFlows[] = [
            'method' => 'Tunai',
            'incomes' => $loanPaymentCash + $incomeCash,
            'orders' => $orderCash,
            'spendings' => $spendingCash,
            'loans' => $loanCash,
            'balance' => ($loanPaymentCash + $incomeCash + $orderCash) - ($spendingCash + $loanCash),
        ];

        return view('cms.admin.finance.cash-flow.index', compact('cashFlows'));
    }
}
