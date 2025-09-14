<?php

namespace App\Http\Controllers\Admin;

use App\Models\Spending;
use App\Models\SpendingType;
use Illuminate\Http\Request;
use App\Exports\SpendingExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BankOwner;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class SpendingController extends Controller
{
    public function index(Request $request)
    {
        $perPages = $request->get('perPage') ?? 5;

        if ($perPages == 'all') {
            $spendings = Spending::all();
        } else {
            $perPage = intval($perPages);
            $spendings = Spending::latest()->paginate($perPage);
        }
        $totalSpending = Spending::selectRaw('SUM(amount * COALESCE(qty, 1)) as total')->value('total');

        return view('cms.admin.finance.spending.index', compact('spendings', 'totalSpending'));
    }

    public function export()
    {
        return Excel::download(new SpendingExport, 'Laporan_Pengeluaran.xlsx');
    }

    public function show(Spending $spending)
    {
        return view('cms.admin.finance.spending.detail', compact('spending'));
    }

    public function create()
    {
        $spendingTypes = SpendingType::latest()->get();
        $banks = BankOwner::all();

        return view('cms.admin.finance.spending.create', compact('spendingTypes', 'banks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'information' => ['required', 'string'],
            'spending_type_id' => ['required'],
            'amount' => ['required', 'numeric'],
            'method' => ['required', 'string'],
            'bank' => ['sometimes', 'nullable', 'string'],
            'qty' => ['sometimes', 'nullable', 'numeric'],
            'date' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $bank = BankOwner::where('id', $request->bank)->first();

                $spending = new Spending();
                $spending->information = $request->information;
                $spending->spending_type_id = $request->spending_type_id;
                $spending->amount = $request->amount;
                $spending->method  = $request->method;
                $spending->bank = $request->method == 'Transfer' ? $bank->name : null;
                $spending->bank_owner_id = $request->method == 'Transfer' ? $request->bank : null;
                $spending->qty = $request->qty ?? 1;
                $spending->total_amount = $request->amount * ($request->qty ?? 1);
                $spending->date = $request->date;
                $spending->save();
            });
            return redirect()->route('spending.index')->with('success', 'Data Berhasil Ditambahkan');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function edit(Spending $spending)
    {
        $spendingTypes = SpendingType::latest()->get();
        $banks = BankOwner::all();

        return view('cms.admin.finance.spending.edit', compact('spending', 'spendingTypes', 'banks'));
    }

    public function update(Request $request, Spending $spending)
    {
        $validator = Validator::make($request->all(), [
            'information' => ['required', 'string'],
            'spending_type_id' => ['required'],
            'amount' => ['required', 'numeric'],
            'method' => ['required', 'string'],
            'bank' => ['sometimes', 'nullable', 'string'],
            'qty' => ['sometimes', 'nullable', 'numeric'],
            'date' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $spending) {
                $bank = BankOwner::where('id', $request->bank)->first();

                $spending->information = $request->information;
                $spending->spending_type_id = $request->spending_type_id;
                $spending->amount = $request->amount;
                $spending->method  = $request->method;
                $spending->bank = $request->method == 'Transfer' ? $bank->name : null;
                $spending->bank_owner_id = $request->method == 'Transfer' ? $request->bank : null;
                $spending->qty = $request->qty ?? 1;
                $spending->total_amount = $request->amount * ($request->qty ?? 1);
                $spending->date = $request->date;
                $spending->save();
            });
            return redirect()->route('spending.index')->with('success', 'Data Berhasil Diupdate');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function destroy(Spending $spending)
    {
        try {
            DB::transaction(function () use ($spending) {
                $spending->delete();
            });
            return redirect()->route('spending.index')->with('success', 'Data Berhasil Dihapus');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }
}
