<?php

namespace App\Http\Controllers\Admin;

use App\Models\Spending;
use App\Models\SpendingType;
use Illuminate\Http\Request;
use App\Exports\SpendingExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class SpendingController extends Controller
{
    public function index(Request $request)
    {
        $spendings = Spending::latest()->paginate(10);
        return view('cms.admin.finance.spending.index', compact('spendings'));
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
        return view('cms.admin.finance.spending.create', compact('spendingTypes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'information' => ['required', 'string'],
            'spending_type_id' => ['required'],
            'amount' => ['required', 'numeric'],
            'method' => ['required', 'string'],
            'bank' => ['sometimes', 'nullable', 'string'],
            'date' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $spending = new Spending();
                $spending->information = $request->information;
                $spending->spending_type_id = $request->spending_type_id;
                $spending->amount = $request->amount;
                $spending->method  = $request->method;
                $spending->bank = $request->method == 'transfer' ? $request->bank : null;
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
        return view('cms.admin.finance.spending.edit', compact('spending', 'spendingTypes'));
    }

    public function update(Request $request, Spending $spending)
    {
        $validator = Validator::make($request->all(), [
            'information' => ['required', 'string'],
            'spending_type_id' => ['required'],
            'amount' => ['required', 'numeric'],
            'method' => ['required', 'string'],
            'bank' => ['sometimes', 'nullable', 'string'],
            'date' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $spending) {
                $spending->information = $request->information;
                $spending->spending_type_id = $request->spending_type_id;
                $spending->amount = $request->amount;
                $spending->method  = $request->method;
                $spending->bank = $request->method == 'transfer' ? $request->bank : null;
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
