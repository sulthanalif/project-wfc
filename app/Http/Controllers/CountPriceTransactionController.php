<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CountPriceTransactionController extends Controller
{
    /**
     * Recount the total price of an order by summing the total price of each order detail.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function count(Order $order)
    {
        try{
            DB::beginTransaction();
            $product = $order->detail()->get();

            foreach ($product as $item) {
                $item->sub_price = $item->product->total_price * $item->qty;
                $item->save();
            }

            $order->total_price = $order->detail()->sum('sub_price');   
            $order->save();
            DB::commit();
            return back()->with('success', 'Data Berhasil Dihitung Ulang!');
        } catch (\Exception $e) {
                DB::rollBack();
                // return response()->json(['message' => $e->getMessage()], 400);
                Log::error($e->getMessage());
        }
    }
}
