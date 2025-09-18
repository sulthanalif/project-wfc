<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankOwner;
use App\Models\Income;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\Spending;
use App\Models\SpendingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $perPages = $request->get('perPage') ?? 5;

        if ($perPages == 'all') {
            $loans = Loan::with('loanPayments')->get();
        } else {
            $perPage = intval($perPages);
            $loans = Loan::with('loanPayments')->latest()->paginate($perPage);
        }

        $totalLoans = $loans->sum('amount');
        $totalPayments = $loans->sum(function ($loan) {
            return $loan->loanPayments->sum('pay');
        });
        $totalRemaining = $totalLoans - $totalPayments;

        $banks = BankOwner::all();

        return view('cms.admin.finance.loan.index', compact('loans', 'totalLoans', 'totalPayments', 'totalRemaining', 'banks'));
    }

    public function show($id)
    {
        $loan = Loan::with('loanPayments')->find($id);
        $banks = BankOwner::all();

        if (!$loan) {
            $data = [
                'message' => 'Data tidak ditemukan',
                'status' => 404
            ];
            return view('cms.error', compact('data'));
        }

        return view('cms.admin.finance.loan.show', compact('loan', 'banks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'borrower_name' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'method' => ['sometimes', 'string'],
            'bank' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'description' => ['sometimes', 'string'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $bank = $request->bank ? BankOwner::find($request->bank) : null;

                $loan = new Loan();
                $loan->borrower_name = $request->borrower_name;
                $loan->amount = $request->amount;
                $loan->method = $request->method;
                $loan->bank_owner_id = $bank->id ?? null;
                $loan->status_payment = 'unpaid';
                $loan->date = $request->date;
                $loan->description = $request->description;
                $loan->save();

                $spendingType = SpendingType::firstOrCreate(['name' => 'Pinjaman/Piutang']);
                Spending::create([
                    'information' => 'Pinjaman/Piutang: ' . $request->borrower_name,
                    'spending_type_id' => $spendingType->id,
                    'amount' => $request->amount,
                    'method' => $request->method,
                    'bank' => $request->method == 'Transfer' && $bank ? $bank->name : null,
                    'qty' => 1,
                    'date' => $request->date,
                    'bank_owner_id' => $bank->id ?? null,
                    'total_amount' => $request->amount * 1,
                ]);
            });
            return redirect()->route('loan.index')->with('success', 'Data Berhasil Ditambahkan');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::find($id);
        if (!$loan) {
            $data = [
                'message' => 'Data tidak ditemukan',
                'status' => 404
            ];
            return view('cms.error', compact('data'));
        }

        $validator = Validator::make($request->all(), [
            'borrower_name' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'upd_method' => ['sometimes', 'string'],
            'upd_bank' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'description' => ['sometimes', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $loan) {
                $bank = $request->upd_bank ? BankOwner::find($request->upd_bank) : null;

                $loan->borrower_name = $request->borrower_name;
                $loan->amount = $request->amount;
                $loan->method = $request->upd_method;
                $loan->bank_owner_id = $bank->id ?? null;
                $loan->date = $request->date;
                $loan->description = $request->description;
                $loan->save();

                $spending = Spending::where('information', 'like', 'Pinjaman/Piutang: ' . $loan->borrower_name . '%')->first();
                $method = $request->upd_method ?? 'Tunai'; // Default to 'Tunai' if not provided
                if ($spending) {
                    $spending->information = 'Pinjaman/Piutang: ' . $request->borrower_name;
                    $spending->amount = $request->amount;
                    $spending->method = $method;
                    $spending->bank = $method == 'Transfer' && $bank ? $bank->name : null;
                    $spending->date = $request->date;
                    $spending->bank_owner_id = $bank->id ?? null;
                    $spending->total_amount = $request->amount * 1;
                    $spending->save();
                }
            });
            return redirect()->route('loan.index')->with('success', 'Data Berhasil Diperbarui');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function destroy($id)
    {
        $loan = Loan::find($id);
        if (!$loan) {
            $data = [
                'message' => 'Data tidak ditemukan',
                'status' => 404
            ];
            return view('cms.error', compact('data'));
        }

        try {
            DB::transaction(function () use ($loan) {
                if ($loan->loanPayments()->count() > 0) {
                    return redirect()->route('loan.index')->with('error', 'Data tidak dapat dihapus karena memiliki pembayaran terkait');
                }

                $loan->delete();
            });
            return redirect()->route('loan.index')->with('success', 'Data Berhasil Dihapus');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function storePayment(Request $request, Loan $loan)
    {
        $validator = Validator::make($request->all(), [
            'pay' => ['required', 'numeric'],
            'method' => ['required', 'string'],
            'bank' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $loan) {
                $photoName = null;
                if ($request->hasFile('photo')) {
                    $photoName = 'payment_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
                    Storage::disk('public')->put('images/loan_payments/' . $photoName, $request->file('photo')->getContent());
                }

                $payment = new LoanPayment([
                    'loan_id' => $loan->id,
                    'pay' => $request->pay,
                    'method' => $request->method,
                    'bank_owner_id' => $request->bank ?? null,
                    'description' => $request->description,
                    'date' => $request->date,
                    'photo' => $photoName
                ]);

                $loan->loanPayments()->save($payment);

                $loan->status_payment = ($loan->amount <= $loan->loanPayments->sum('pay')) ? 'paid' : 'pending';
                $loan->save();
            });
            return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function verifyPayment(Request $request, LoanPayment $payment)
    {
        try {
            DB::transaction(function () use ($request, $payment) {
                $payment->is_confirmed = !$payment->is_confirmed;
                $payment->save();

                $income = new Income([
                    'information' => 'Pembayaran Pinjaman: ' . $payment->loan->borrower_name,
                    'amount' => $payment->pay,
                    'method' => $payment->method,
                    'bank' => $payment->method == 'Transfer' && $payment->bankOwner ? $payment->bankOwner->name : null,
                    'date' => $payment->date,
                    'bank_owner_id' => $payment->bank_owner_id,
                ]);
                $income->save();
            });
            return redirect()->back()->with('success', 'Status Pembayaran Berhasil Diverifikasi');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function deletePayment(Loan $loan, $payment)
    {
        $payment = LoanPayment::find($payment);
        if (!$payment) {
            $data = [
                'message' => 'Data tidak ditemukan',
                'status' => 404
            ];
            return view('cms.error', compact('data'));
        }

        try {
            DB::transaction(function () use ($payment) {
                if ($payment->photo) {
                    Storage::disk('public')->delete('images/loan_payments/' . $payment->photo);
                }
                $payment->delete();
            });
            return redirect()->back()->with('success', 'Pembayaran berhasil dihapus');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }
}
