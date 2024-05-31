<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SubProduct;
use Illuminate\Support\Facades\Validator;

class SubProductController extends Controller
{
    public function index(Request $request)
    {
        $subProducts = SubProduct::paginate(10);

        return view('cms.admin.sub-products.index', compact('subProducts'));
    }

    public function create()
    {
        return view('cms.admin.sub-products.create');
    }

    public function show(SubProduct $subProduct)
    {
        return view('cms.admin.sub-products.detail', compact($subProduct));
    }

    public function store(Request $request, Product $product)
    {
        // return response()->json('haii');
        $validator = Validator::make($request->all(), [
            // 'product_id' => 'required|string',
            'name' => 'required|string',
            'unit' => 'required|string',
            'price' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return view('cms.error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $product){
                $subProduct = new SubProduct([
                    'name' => $request->name,
                    'unit' => $request->unit,
                    'price' => $request->price
                ]);
                $subProduct->save();
            });

            return redirect()->route('sub-product.index')->with('success', 'Data Berhasil Ditambahkan!');
        } catch (\Exception $error) {
            $data = [
                'message' => $error->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function edit(SubProduct $subProduct)
    {
        return view('cms.admin.sub-products.edit', compact('subProduct'));
    }

    public function update(Request $request, SubProduct $subProduct)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'unit' => 'required|string',
            'price' => 'required|numeric'

        ]);

        if ($validator->fails()) {
            return view('cms.error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $subProduct) {
                $subProduct->update([
                    'name' => $request->name,
                    'unit' => $request->unit,
                    'price' => $request->price
                ]);
            });
            return redirect()->route('sub-product.index')->with('success', 'Data Berhasil Diubah!');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function destroy(Product $product, SubProduct $subProduct)
    {
        try {
            DB::transaction(function () use ($subProduct) {
                $subProduct->delete();
            });
            return redirect()->route('sub-product.index')->with('success', 'Data Berhasil Dihapus!');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

}
