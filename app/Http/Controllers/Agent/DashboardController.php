<?php

namespace App\Http\Controllers\Agent;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubAgent;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = Auth::user();
        $myOrders = Order::where('agent_id', $agent->id)->count();
        $subAgents = SubAgent::where('agent_id', $agent->id)->count();
        $totalPriceOrder = 0;
        $totalDeposit = 0;

        //total price order
        foreach ($agent->order as $order) {
            $totalPriceOrder += $order->total_price;

            //total deposit
            foreach ($order->payment as $payment) {
                $totalDeposit += $payment->pay;
            }
        }

        if ($totalPriceOrder > 0) {
            $datas[] = [
                'agent_name' => $agent->agentProfile->name,
                'total_price_order' => $totalPriceOrder,
                'total_deposit' => $totalDeposit,
                'total_remaining_payment' => $totalPriceOrder - $totalDeposit
            ];
        }

        if (is_array($datas)) {
            $totalPriceOrderAll = array_sum(array_column($datas, 'total_price_order'));
            $totalDepositAll = array_sum(array_column($datas, 'total_deposit'));
            $totalRemainingAll = array_sum(array_column($datas, 'total_remaining_payment'));
        }

        $stats = [
            'totalOrder' => $myOrders,
            'totalPriceOrde' => $totalPriceOrderAll,
            'totalDeposit' => $totalDepositAll,
            'totalRemaining' => $totalRemainingAll,
            'totalSubAgent' => $subAgents
        ];

        return response()->json([
            'stats' => $stats
        ]);

        return view('cms.agen.index', compact('stats'));
    }

    public function noActive()
    {
        return view('cms.agen.noactive');
    }
}
