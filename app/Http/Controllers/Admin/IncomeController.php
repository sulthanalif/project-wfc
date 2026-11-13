<?php

namespace App\Http\Controllers\Admin;

use App\Exports\IncomeExport;
use App\Models\Income;
use Illuminate\Http\Request;
use App\Helpers\TableFilter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BankOwner;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Income::with('bankOwner')->latest();

        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('information', 'like', "%{$search}%")
                    ->orWhere('method', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhereHas('bankOwner', fn($b) => $b->where('name', 'like', "%{$search}%"));
            });
        }

        $filters = TableFilter::apply($query, $request, [
            'period' => ['label' => 'Periode', 'type' => 'period', 'apply' => TableFilter::byPeriodDates('date')],
            'method' => ['label' => 'Metode', 'type' => 'select', 'options' => ['tunai' => 'Tunai', 'transfer' => 'Transfer']],
            'bank_owner_id' => ['label' => 'Rekening', 'type' => 'select', 'options' => BankOwner::orderBy('name')->pluck('name', 'id')->all()],
            'date' => ['label' => 'Tanggal', 'type' => 'date_range'],
        ]);
        $totalIncome = (clone $query)->sum('amount');

        $perPage = min(max((int) ($request->get('perPage') === 'all' ? 50 : $request->get('perPage', 10)), 1), 50);
        $incomes = $query->paginate($perPage)->withQueryString();
        return view('cms.admin.finance.income.index', compact('incomes', 'totalIncome', 'filters'));
    }

    public function export(Request $request)
    {
        return Excel::download(new IncomeExport($request->start_date, $request->end_date), 'Laporan_Pemasukan.xlsx');
    }

    public function create()
    {
        $banks = BankOwner::all();
        
        return view('cms.admin.finance.income.create', compact('banks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'information' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'method' => ['required', 'string'],
            'bank' => ['sometimes', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $bank = BankOwner::where('id', $request->bank)->first();

                $income = new Income();
                $income->information = $request->information;
                $income->amount = $request->amount;
                $income->method  = $request->method;
                $income->bank = $request->method == 'Transfer' ? $bank->name : null;
                $income->bank_owner_id = $request->method == 'Transfer' ? $request->bank : null;
                $income->date = $request->date;
                $income->save();
            });
            return redirect()->route('income.index')->with('success', 'Data Berhasil Ditambahkan');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function show(Income $income)
    {
        return view('cms.admin.finance.income.detail', compact('income'));
    }

    public function edit(Income $income)
    {
        $banks = BankOwner::all();

        return view('cms.admin.finance.income.edit', compact('income', 'banks'));
    }

    public function update(Request $request, Income $income)
    {
        $validator = Validator::make($request->all(), [
            'information' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'method' => ['required', 'string'],
            'bank' => ['sometimes', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $income) {
                $bank = BankOwner::where('id', $request->bank)->first();
                
                $income->information = $request->information;
                $income->amount = $request->amount;
                $income->method  = $request->method;
                $income->bank = $request->method == 'Transfer' ? $bank->name : null;
                $income->bank_owner_id = $request->method == 'Transfer' ? $request->bank : null;
                $income->date = $request->date;
                $income->save();
            });
            return redirect()->route('income.index')->with('success', 'Data Berhasil Diubah');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function destroy(Income $income)
    {
        try {
            DB::transaction(function () use ($income) {
                $income->delete();
            });
            return redirect()->route('income.index')->with('success', 'Data Berhasil Dihapus');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }
}
