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
    public function index()
    {
        $datas = BankOwner::paginate(5);

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
        try {
            DB::transaction(function () use ($bankOwner) {
                if ($bankOwner->payments()->count() > 0) {
                    return back()->with('error', 'Bank masih digunakan di pembayaran');
                }

                $bankOwner->delete();
            });

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
