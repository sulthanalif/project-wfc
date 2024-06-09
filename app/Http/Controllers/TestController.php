<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        $data = [];
        foreach ($orders as $order) {
            $orderDetails = $order->detail;
            foreach ($orderDetails as $orderDetail) {
                    $data [] = [
                        'order_dari' => $orderDetail->subAgent ? 'sub_agent_'.$orderDetail->subAgent->name : $order->agent->agentProfile->name,
                    ];
            }
        }

        return response()->json($data, 200);
    }
}
