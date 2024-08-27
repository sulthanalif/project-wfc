<?php

namespace App\Http\Controllers\Admin;

use App\Exports\IncomeExport;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $perPages = $request->get('perPage') ?? 5;

        if ($perPages == 'all') {
            $incomes = Income::all();
        } else {
            $perPage = intval($perPages);
            $incomes = Income::latest()->paginate($perPage);
        }
        $totalIncome = Income::sum('amount');
        return view('cms.admin.finance.income.index', compact('incomes', 'totalIncome'));
    }

    public function export(Request $request)
    {
        return Excel::download(new IncomeExport($request->start_date, $request->end_date), 'Laporan_Pemasukan.xlsx');
    }

    public function create()
    {
        return view('cms.admin.finance.income.create');
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
                $income = new Income();
                $income->information = $request->information;
                $income->amount = $request->amount;
                $income->method  = $request->method;
                $income->bank = $request->method == 'transfer' ? $request->bank : null;
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
        return view('cms.admin.finance.income.edit', compact('income'));
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
                $income->information = $request->information;
                $income->amount = $request->amount;
                $income->method  = $request->method;
                $income->bank = $request->method == 'transfer' ? $request->bank : null;
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
