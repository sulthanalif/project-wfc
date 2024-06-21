<?php

namespace App\Http\Controllers\Transaction;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class DetailOrderController extends Controller
{
    public function editDetail(Request $request, OrderDetail $detail)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'string',
            'qty' => 'numeric'
        ]);

        if ($validator->fails()){
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $detail) {
                $detail->update($request->all());
            });

            return back()->with('success', 'Data Berhasil Diperbaharui!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function getLikeProduct($product)
    {
        $result = Product::where('name', 'like', '%' . $product . '%')->get();
        
    }
}
