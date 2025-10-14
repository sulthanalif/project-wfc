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
use App\Http\Controllers\Controller;

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
            // $loanPayment = LoanPayment::where('method', 'transfer')
            //     ->where('bank_owner_id', $bank->id)
            //     ->sum('pay');
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
                'incomes' => $income,
                'orders' => $order,
                'spendings' => $spending,
                'loans' => $loan,
                'balance' => ($income + $order) - ($spending + $loan),
            ];
        }

        // Ambil data tunai
        $incomeCash = Income::where('method', 'Tunai')->sum('amount');
        // $loanPaymentCash = LoanPayment::where('method', 'Tunai')->sum('pay');
        $orderCash = Payment::where('status', 'accepted')
            ->where('method', 'Tunai')
            ->sum('pay');
        $spendingCash = Spending::where('method', 'Tunai')->sum('amount');
        $loanCash = Loan::where('method', 'Tunai')->sum('amount');

        $cashFlows[] = [
            'method' => 'Tunai',
            'incomes' => $incomeCash,
            'orders' => $orderCash,
            'spendings' => $spendingCash,
            'loans' => $loanCash,
            'balance' => ($incomeCash + $orderCash) - ($spendingCash + $loanCash),
        ];

        return view('cms.admin.finance.cash-flow.index', compact('cashFlows'));
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
