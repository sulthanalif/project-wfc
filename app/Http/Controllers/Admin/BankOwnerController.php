<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BankOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BankOwner::latest();

        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('account_number', 'like', "%{$search}%")
                    ->orWhere('account_name', 'like', "%{$search}%");
            });
        }

        $datas = $query->paginate(10)->withQueryString();

        return view('cms.admin.banks.index', compact('datas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'account_number' => 'required|numeric',
            'account_name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        $datas = $validator->validated();

        try {
            DB::transaction(function () use ($datas) {
                BankOwner::create($datas);
            });

            return back()->with('success', 'Bank telah ditambahkan');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];

            return view('cms.error', compact('data'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankOwner $bankOwner)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'account_number' => 'required|numeric',
            'account_name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        $datas = $validator->validated();

        try {
            DB::transaction(function () use ($bankOwner, $datas) {
                $bankOwner->update($datas);
            });

            return back()->with('success', 'Bank berhasil diubah');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];

            return view('cms.error', compact('data'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankOwner $bankOwner)
    {
        $hasRelations = $bankOwner->payments()->exists()
            || $bankOwner->incomes()->exists()
            || $bankOwner->spendings()->exists()
            || $bankOwner->loans()->exists()
            || $bankOwner->loanPayments()->exists();

        if ($hasRelations) {
            return back()->with('error', 'Rekening bank tidak dapat dihapus karena masih digunakan pada data transaksi.');
        }

        try {
            $bankOwner->delete();
            return back()->with('success', 'Bank telah dihapus');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];

            return view('cms.error', compact('data'));
        }
    }
}
