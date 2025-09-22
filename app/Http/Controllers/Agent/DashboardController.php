<?php

namespace App\Http\Controllers\Agent;

use App\Models\User;
use App\Models\Order;
use App\Models\Reward;
use App\Models\SubAgent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = Auth::user();
        $myOrders = Order::where('agent_id', $agent->id)->where('status', 'accepted')->count();
        $subAgents = SubAgent::where('agent_id', $agent->id)->count();
        $totalPriceOrder = 0;
        $totalDeposit = 0;
        $totalProduct = 0;

        //total price order
        foreach ($agent->order as $order) {
            if ($order->status == 'accepted') {
                $totalPriceOrder += $order->total_price;

                //total deposit
                $payments = $order->payment->where('status', 'accepted');
                foreach ($payments as $payment) {
                    $totalDeposit += $payment->pay;
                }

                foreach ($order->detail as $detail) {
                    $totalProduct += $detail->qty;
                }
            }
        }

        $activeRewards = Reward::whereHas('period', function ($query) {
            $query->where('start_date', '<=', now())->where('end_date', '>=', now());
        })->orderBy('target_qty', 'desc')->get();

        $agentRewardTitle = 'Tidak ada reward';
        foreach ($activeRewards as $reward) {
            if ($totalProduct >= $reward->target_qty) {
                $agentRewardTitle = $reward->title;
                $rewardImage = $reward->image;
                break; // <-- Hentikan loop karena reward tertinggi sudah didapat
            }
        }
        



        $datas[] = [
            'agent_name' => $agent->agentProfile->name,
            'total_price_order' => $totalPriceOrder,
            'total_deposit' => $totalDeposit,
            'total_remaining_payment' => $totalPriceOrder - $totalDeposit,
            'reward_image' => $rewardImage ?? '',
            'total_product' => $totalProduct,
            'reward' => $agentRewardTitle,
        ];


        if (is_array($datas)) {
            $totalPriceOrderAll = array_sum(array_column($datas, 'total_price_order'));
            $totalDepositAll = array_sum(array_column($datas, 'total_deposit'));
            $totalRemainingAll = array_sum(array_column($datas, 'total_remaining_payment'));
            $totalProductAll = array_sum(array_column($datas, 'total_product'));
        }

        $stats = [
            'totalOrder' => $myOrders,
            'totalPriceOrder' => $totalPriceOrderAll,
            'totalDeposit' => $totalDepositAll,
            'totalRemaining' => $totalRemainingAll,
            'totalSubAgent' => $subAgents,
            'totalProduct' => $totalProductAll,
            'reward' => $agentRewardTitle,
            'reward_image' => $rewardImage ?? '',
        ];

        // return response()->json([
        //     'stats' => $stats
        // ]);

        return view('cms.agen.index', compact('stats'));
    }

    public function noActive()
    {
        return view('cms.agen.noactive');
    }
}
