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
                    if ($request->qty <= 0) {
                        $detail->delete();
                    } else {
                        $detail->qty = $request->qty;
                        $detail->sub_price = $product->total_price * $request->qty;
                        $detail->save();
                    }
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

                if ($order->payment()->count() > 0) {
                    if ($order->payment()->sum('pay') == $order->total_price) {
                        $order->payment_status = 'paid';
                        $order->save();
                    } else {
                        $order->payment_status = 'pending';
                        $order->save();
                    }
                }
            });

            return back()->with('success', 'Data Berhasil Diperbaharui!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function addItems(Request $request, Order $order)
    {
        // return response()->json($request->all());
        try {
            DB::transaction(function () use ($request, $order, &$updateOrder) {


                $products = json_decode($request->products, true);

                // dd($products);

                // Membuat OrderDetail untuk setiap produk
                foreach ($products as $product) {
                    // Mendapatkan detail produk berdasarkan productId
                    $productDetail = Product::findOrFail($product['productId']);

                    // Membuat OrderDetail untuk setiap produk
                    OrderDetail::create([
                        'order_id' => $order->id,
                        // 'sub_agent_id' => $request->sub_agent_item,
                        'sub_agent_id' => $product['subAgentId'],
                        // 'name' => $productDetail->name,
                        'product_id' => $product['productId'],
                        // 'price' => $productDetail->price,
                        'sub_price' => $product['subTotal'],
                        'qty' => $product['qty']
                    ]);
                }

                // $access_date = AccessDate::first();
                $updateOrder = $order->update([
                    'total_price' => $order->total_price + $request->total_price,
                ]);

                if ($order->payment()->count() > 0) {
                    if ($order->payment()->sum('pay') == $order->total_price) {
                        $order->payment_status = 'paid';
                        $order->save();
                    } else {
                        $order->payment_status = 'pending';
                        $order->save();
                    }
                }
            });
            if ($updateOrder) {
                return redirect()->back()->with('success', 'Item Telah Ditambahkan');
            } else {
                return redirect()->back()->with('error', 'Item Gagal Ditambahkan');
            }
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
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
            DB::transaction(function () use ($request) {});
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
