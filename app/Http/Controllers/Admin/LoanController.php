<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankOwner;
use App\Models\Loan;
use App\Models\LoanPayment;
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

        return view('cms.admin.finance.loan.index', compact('loans', 'totalLoans', 'totalPayments', 'totalRemaining'));
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
            'date' => ['required', 'date'],
            'description' => ['sometimes', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $loan = new Loan();
                $loan->borrower_name = $request->borrower_name;
                $loan->amount = $request->amount;
                $loan->date = $request->date;
                $loan->description = $request->description;
                $loan->save();
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
            'date' => ['required', 'date'],
            'description' => ['sometimes', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $loan) {
                $loan->borrower_name = $request->borrower_name;
                $loan->amount = $request->amount;
                $loan->date = $request->date;
                $loan->description = $request->description;
                $loan->save();
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
