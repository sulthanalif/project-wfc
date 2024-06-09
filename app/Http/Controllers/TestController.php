<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {

        $order = Order::find('dc6eeb71-835e-434e-88b8-7c902411ef39');
        $orderDetails = $order->detail->toArray() ?? [];
        $qty = array_sum(array_column($orderDetails, 'qty'));

        $totalQty = 0;
        $distribution = Distribution::where('order_id', $order->id)->get();
        foreach ($distribution as $item) {
            $totalQty += $item->detail->sum('qty');
        }


        return response()->json([
            'qty' => $qty,
            'datas' => $totalQty
        ]);
    }
}
