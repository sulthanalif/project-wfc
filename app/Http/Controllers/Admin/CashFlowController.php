<?php

namespace App\Http\Controllers\Admin;

use App\Models\Loan;
use App\Models\Income;
use App\Models\Payment;
use App\Models\Spending;
use App\Models\BankOwner;
use App\Models\LoanPayment;
use App\Models\SpendingType;
use Illuminate\Http\Request;
use App\Models\Period;
use App\Helpers\TableFilter;
use App\Http\Controllers\Controller;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        $cashFlows = [];

        // Default semua periode: halaman ini menampilkan saldo. Bila periode dipilih, angka menjadi arus kas selama periode itu.
        $filters = TableFilter::apply(BankOwner::query(), $request, [
            'period' => ['label' => 'Periode', 'type' => 'period', 'default' => 'all', 'apply' => null],
        ]);
        $period = $filters['period']['value'] === 'all' ? null : Period::find($filters['period']['value']);
        $inPeriod = function ($q) use ($period) {
            if ($period) {
                $q->whereDate('date', '>=', $period->start_date)->whereDate('date', '<=', $period->end_date);
            }
        };

        // Ambil data bank transfer
        $banksQuery = BankOwner::query();
        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $banksQuery->where('name', 'like', "%{$search}%");
        }
        $banks = $banksQuery->get();
        foreach ($banks as $bank) {
            $income = Income::where('bank_owner_id', $bank->id)
                ->where('method', 'transfer')
                ->where($inPeriod)->sum('amount');
            // $loanPayment = LoanPayment::where('method', 'transfer')
            //     ->where('bank_owner_id', $bank->id)
            //     ->where($inPeriod)->sum('pay');
            $order = $bank->payments()
                    ->where('status', 'accepted')
                    ->where('bank_owner_id', $bank->id)
                    ->where('method', 'transfer')
                    ->where($inPeriod)->sum('pay');
            $spending = Spending::where('method', 'transfer')
                ->where('bank_owner_id', $bank->id)
                ->where($inPeriod)->sum('total_amount');
            $loan = Loan::where('method', 'transfer')
                ->where('bank_owner_id', $bank->id)
                ->where($inPeriod)->sum('amount');
            $cashFlows[] = [
                'method' => $bank->name,
                'incomes' => $income,
                'orders' => $order,
                'spendings' => $spending,
                'loans' => $loan,
                'balance' => ($income + $order) - ($spending + $loan),
            ];
        }

        // Ambil data tunai
        $incomeCash = Income::where('method', 'Tunai')->where($inPeriod)->sum('amount');
        // $loanPaymentCash = LoanPayment::where('method', 'Tunai')->where($inPeriod)->sum('pay');
        $orderCash = Payment::where('status', 'accepted')
            ->where('method', 'Tunai')
            ->where($inPeriod)->sum('pay');
        $spendingCash = Spending::where('method', 'Tunai')->where($inPeriod)->sum('total_amount');
        $loanCash = Loan::where('method', 'Tunai')->where($inPeriod)->sum('amount');

        $cashFlows[] = [
            'method' => 'Tunai',
            'incomes' => $incomeCash,
            'orders' => $orderCash,
            'spendings' => $spendingCash,
            'loans' => $loanCash,
            'balance' => ($incomeCash + $orderCash) - ($spendingCash + $loanCash),
        ];

        return view('cms.admin.finance.cash-flow.index', compact('cashFlows', 'filters'));
    }

    public function create()
    {
        $banks = BankOwner::all();
        return view('cms.admin.finance.cash-flow.create', compact('banks'));
    }

    public function transferCash(Request $request)
    {
        // Validate request
        $request->validate([
            'from_bank_id' => 'required|exists:bank_owners,id',
            'to_bank_id' => 'required|exists:bank_owners,id|different:from_bank_id',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string'
        ]);

        try {
            // Get bank details
            $fromBank = BankOwner::findOrFail($request->from_bank_id);
            $toBank = BankOwner::findOrFail($request->to_bank_id);

            $spendingType = SpendingType::firstOrCreate([
                'name' => 'Transfer'
            ]);

            // Create spending record for source bank
            $spending = new Spending();
            $spending->spending_type_id = $spendingType->id;
            $spending->information =  "Transfer to {$toBank->name} " . ($request->description ? " : {$request->description}" : '');
            $spending->amount = $request->amount;
            $spending->method = 'Transfer';
            $spending->bank_owner_id = $fromBank->id;
            $spending->qty = 1;
            $spending->bank = $fromBank->name;
            $spending->date = now();
            $spending->total_amount = $request->amount;
            $spending->save();

            // Create income record for destination bank
            $income = new Income();
            $income->bank_owner_id = $toBank->id;
            $income->amount = $request->amount;
            $income->information =  "Transfer from {$fromBank->name} " . ($request->description ? " : {$request->description}" : '');
            $income->date = now();
            $income->method = 'Transfer';
            $income->bank = $toBank->name;
            $income->save();

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Pindah Kas Berhasil'
            // ]);

            return redirect()->route('cash-flow.index')->with('success', 'Pindah Kas Berhasil');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Pindah Kas: ' . $e->getMessage()
            ], 500);
        }
    }
}
