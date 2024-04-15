<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::paginate(10);

        return view('cms.admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.admin.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255', 'string'],
            'address' => ['required', 'max:500', 'string'],
            'phone_number' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, &$store) {
                $store = Supplier::create([
                    'name' => $request->name,
                    'address' => $request->address,
                    'phone_number' => $request->phone_number,
                ]);
            });
            if ($store) {
                return redirect()->route('supplier.index')->with('success', 'Data Berhasil Disimpan!');
            } else {
                return back()->with('error', 'Data Gagal Disimpan!');
            }
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];

            return view('cms.error', compact('data'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        if ($supplier) {
            return view('cms.admin.suppliers.detail', compact('supplier'));
        } else {
            return back()->with('error', 'Data Tidak Ditemukan!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        if ($supplier) {
            return view('cms.admin.suppliers.edit', compact('supplier'));
        } else {
            return back()->with('error', 'Data Tidak Ditemukan!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255', 'string'],
            'address' => ['required', 'max:500', 'string'],
            'phone_number' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $supplier, &$update) {
                $update = $supplier->update([
                    'name' => $request->name,
                    'address' => $request->address,
                    'phone_number'=> $request->phone_number
                ]);
            });
            if ($update) {
                return redirect()->route('supplier.index')->with('success', 'Data Berhasil Diubah!');
            } else {
                return back()->with('error', 'Data Gagal Diubah!');
            }
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];

            return view('cms.error', compact('data'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Supplier $supplier)
    {
        $delete = $supplier->delete();

        if ($delete) {
            return redirect()->route('supplier.index', ['page' => $request->page])->with('success', 'Data Berhasil Dihapus!');
        } else {
            return back()->with('error', 'Data Gagal Dihapus!');
        }
    }
}
