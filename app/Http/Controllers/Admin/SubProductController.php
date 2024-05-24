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
    public function store(Request $request, Product $product)
    {
        // return response()->json('haii');
        $validator = Validator::make($request->all(), [
            // 'product_id' => 'required|string',
            'name' => 'required|string',
            'unit' => 'required|string',
            'amount' => 'numeric|required',
            'price' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $product){
                $subProduct = new SubProduct([
                    'product_id' => $product->id,
                    'name' => $request->name,
                    'unit' => $request->unit,
                    'amount' => $request->amount,
                    'price' => $request->price
                ]);
                $subProduct->save();
            });

            return redirect()->route('product.show', $product)->with('success', 'Data BErhasil Ditambahkan!');
        } catch (\Exception $error) {
            $data = [
                'message' => $error->getMessage(),
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
            return redirect()->route('product.show', $product)->with('success', 'Data Berjasil Dihapus!');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }
}
