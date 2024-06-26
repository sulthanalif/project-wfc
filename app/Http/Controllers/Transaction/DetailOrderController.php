<?php

namespace App\Http\Controllers\Transaction;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
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
                $product = Product::find($request->product_id);
                $order = Order::find($detail->order_id);
                $payments = Payment::where('order_id', $order->id)->orderBy('created_at', 'asc')->get();

                $detail->product_id = $request->product_id;
                $detail->sub_price = ($product->price * $product->days) * $request->qty;
                $detail->qty = $request->qty;
                $detail->save();

                $order->total_price = $order->detail->sum('sub_price');
                $order->save();
                
                if($payments->count() > 0){
                    foreach ($payments as $key => $payment) {
                        $payment->remaining_payment = $key == 0 ? $order->total_price - $payment->pay : $payments[$key - 1]->remaining_payment - $payment->pay;
                        $payment->save();
                    }
                }

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
