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

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $detail) {
                $product = Product::find($request->product_id);
                $order = Order::find($detail->order_id);
                $payments = Payment::where('order_id', $order->id)->orderBy('created_at', 'asc')->get();

                $product_safe_point = Product::query()
                    ->where('name', 'like', "%{$product->name}%")
                    ->where('is_safe_point', true)
                    ->first();

                if ($detail->product_id == $request->product_id) {
                    $detail->qty = $request->qty;
                    $detail->sub_price = $product->total_price * $request->qty;
                    $detail->save();
                } else {
                    if ($product_safe_point) {
                        $existingSafePointDetail = OrderDetail::where('order_id', $order->id)
                            ->where('product_id', $product_safe_point->id)
                            ->where('sub_agent_id', $detail->sub_agent_id)
                            ->first();

                        if ($existingSafePointDetail) {
                            $existingSafePointDetail->qty = $existingSafePointDetail->qty + $request->qty;
                            $existingSafePointDetail->sub_price = $product_safe_point->total_price * $request->qty;
                            $existingSafePointDetail->save();
                        } else {
                            $newOrderDetail = new OrderDetail();
                            $newOrderDetail->order_id = $order->id;
                            $newOrderDetail->product_id = $request->product_id;
                            $newOrderDetail->sub_agent_id = $detail->sub_agent_id;
                            $newOrderDetail->qty = $request->qty;
                            $newOrderDetail->sub_price = $product_safe_point->total_price * $request->qty;
                            $newOrderDetail->save();
                        }

                        $detail->qty = $detail->qty - $request->qty;
                        if ($detail->qty <= 0) {
                            $detail->delete();
                        } else {
                            $detail->sub_price = $detail->product->total_price * $detail->qty;
                            $detail->save();
                        }
                    } else {
                        $detail->product_id = $request->product_id;
                        $detail->sub_agent_id = $request->sub_agent_id;
                        $detail->qty = $request->qty;
                        $detail->sub_price = $product->total_price * $request->qty;
                        $detail->save();
                    }
                }

                $order->total_price = $order->detail->sum('sub_price');
                $order->save();

                if ($payments->count() > 0) {
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

    public function safePoint(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'detail_id' => 'required',
            'qty' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request) {
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
