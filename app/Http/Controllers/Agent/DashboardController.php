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
        $subAgents = SubAgent::where('agent_id', $agent->id)->get();

        $agentOrders = Order::where('agent_id', $agent->id)
            ->where('status', 'accepted')
            ->whereHas('detail.product.package.package.period', function ($query) {
                $query->where('is_active', 1);
            })
            ->whereHas('detail', function ($detailQuery) {
                $detailQuery->whereNull('sub_agent_id');
            })
            ->get();

        $ordersCount = $agentOrders->count();

        $stats = [
            'totalOrder' => $ordersCount,
            'totalPriceOrder' => 0,
            'totalDeposit' => 0,
            'totalRemaining' => 0,
            'totalSubAgent' => $subAgents->count(),
            'totalProduct' => 0,
            'reward' => 'Tidak ada reward',
            'reward_image' => '',
            'reward_data' => [],
        ];

        $rewardData = [
            'agent' => $this->buildRewardSummary(
                optional($agent->agentProfile)->name ?? $agent->email,
                $agentOrders,
                fn ($detail) => is_null($detail->sub_agent_id)
            ),
        ];

        foreach ($subAgents as $subAgent) {
            $subAgentOrders = Order::where('status', 'accepted')
                ->whereHas('detail.product.package.package.period', function ($query) {
                    $query->where('is_active', 1);
                })
                ->whereHas('detail', function ($detailQuery) use ($subAgent) {
                    $detailQuery->where('sub_agent_id', $subAgent->id);
                })
                ->get();

            $rewardData['sub_agents'][] = $this->buildRewardSummary(
                $subAgent->name,
                $subAgentOrders,
                fn ($detail) => (string) $detail->sub_agent_id === (string) $subAgent->id
            );
        }

        $stats['totalPriceOrder'] = $rewardData['agent']['total_price_order'];
        $stats['totalDeposit'] = $rewardData['agent']['total_deposit'];
        $stats['totalRemaining'] = $rewardData['agent']['total_remaining_payment'];
        $stats['totalProduct'] = $rewardData['agent']['total_product'];
        $stats['reward'] = $rewardData['agent']['reward'];
        $stats['reward_image'] = $rewardData['agent']['reward_image'];
        $stats['reward_data'] = $rewardData;

        return view('cms.agen.index', compact('stats'));
    }

    private function buildRewardSummary(string $name, $orders, ?callable $detailFilter = null): array
    {
        $totalPriceOrder = 0;
        $totalDeposit = 0;
        $totalProduct = 0;

        foreach ($orders as $order) {
            if ($order->status !== 'accepted') {
                continue;
            }

            $details = $order->detail;
            if ($detailFilter) {
                $details = $details->filter($detailFilter);
            }

            foreach ($details as $detail) {
                $totalPriceOrder += (float) $detail->sub_price;
                $totalProduct += (int) $detail->qty;
            }

            foreach ($order->payment->where('status', 'accepted') as $payment) {
                $totalDeposit += (float) $payment->pay;
            }
        }

        $activeRewards = Reward::whereHas('period', function ($query) {
            $query->where('is_active', 1);
        })->orderBy('target_qty', 'desc')->get();

        $rewardTitle = 'Tidak ada reward';
        $rewardImage = '';

        foreach ($activeRewards as $reward) {
            if ($totalProduct >= $reward->target_qty) {
                $rewardTitle = $reward->title;
                $rewardImage = $reward->image;
                break;
            }
        }

        return [
            'name' => $name,
            'total_price_order' => $totalPriceOrder,
            'total_deposit' => $totalDeposit,
            'total_remaining_payment' => $totalPriceOrder - $totalDeposit,
            'total_product' => $totalProduct,
            'reward' => $rewardTitle,
            'reward_image' => $rewardImage,
        ];
    }

    public function noActive()
    {
        return view('cms.agen.noactive');
    }
}
