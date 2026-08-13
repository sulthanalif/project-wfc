<?php

namespace App\Http\Controllers\Agent;

use App\Models\User;
use App\Models\Order;
use App\Models\Reward;
use App\Models\SubAgent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Commission;
use App\Models\OrderDetail;

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

        $commissionData = $this->computeCommissions($agent);

        return view('cms.agen.index', compact('stats', 'commissionData'));
    }

    /**
     * Compute commissions for the given agent and its sub-agents.
     * Returns summary for agent and each sub-agent.
     */
    private function computeCommissions($agent)
    {
        $activeCommissions = Commission::whereHas('package.period', function ($q) {
            $q->where('is_active', 1);
        })->get();

        $subAgentModels = SubAgent::where('agent_id', $agent->id)->get();
        $subAgentIds = $subAgentModels->pluck('id')->toArray();

        $agentSummary = [];
        $subAgentsSummary = [];
        $rows = [];

        // initialize subAgentsSummary
        foreach ($subAgentModels as $s) {
            $subAgentsSummary[$s->id] = [
                'id' => $s->id,
                'name' => $s->name,
                'total_bonus' => 0,
                'commissions' => [],
            ];
        }

        $agentTotalBonus = 0;

        foreach ($activeCommissions as $commission) {
            $packageId = $commission->package_id;

            $isPercentage = strpos($commission->reward, '%') !== false;
            $bonusPercentage = 0;
            $bonusValue = 0;
            if ($isPercentage) {
                preg_match('/([0-9.]+)/', $commission->reward, $matches);
                $bonusPercentage = isset($matches[1]) ? floatval($matches[1]) : 0;
            } else {
                $bonusValue = floatval(preg_replace('/[^0-9.]/', '', $commission->reward)) ?: 0;
            }

            // Agent direct orders (sub_agent_id null)
            $agentDetails = OrderDetail::whereHas('order', function ($q) use ($agent) {
                    $q->where('status', 'accepted')
                        ->where('agent_id', $agent->id);
                })
                ->whereNull('sub_agent_id')
                ->whereHas('product.package.package', function ($q) use ($packageId) {
                    $q->where('id', $packageId)
                        ->whereHas('period', function ($q) {
                            $q->where('is_active', 1);
                        });
                })
                ->get();

            $agentProductCount = $agentDetails->sum('qty');
            $agentTotalPrice = $agentDetails->sum(function ($d) {
                return $d->sub_price * $d->qty;
            });

            if ($isPercentage) {
                $agentBonus = $agentTotalPrice * $bonusPercentage / 100;
            } else {
                $agentBonus = $bonusValue * $agentProductCount;
            }

            $agentSummary[] = [
                'commission_id' => $commission->id,
                'title' => $commission->title,
                'total_product' => $agentProductCount,
                'total_bonus' => $agentBonus,
            ];

            // sum sub-agents for this commission
            $subTotalProduct = 0;
            $subTotalBonus = 0;
            foreach ($subAgentModels as $sub) {
                $subDetails = OrderDetail::whereHas('order', function ($q) {
                        $q->where('status', 'accepted');
                    })
                    ->where('sub_agent_id', $sub->id)
                    ->whereHas('product.package.package', function ($q) use ($packageId) {
                        $q->where('id', $packageId)
                            ->whereHas('period', function ($q) {
                                $q->where('is_active', 1);
                            });
                    })
                    ->get();

                $subProductCount = $subDetails->sum('qty');
                $subTotalPrice = $subDetails->sum(function ($d) {
                    return $d->sub_price * $d->qty;
                });

                if ($isPercentage) {
                    $subBonus = $subTotalPrice * $bonusPercentage / 100;
                } else {
                    $subBonus = $bonusValue * $subProductCount;
                }

                $subAgentsSummary[$sub->id]['commissions'][] = [
                    'commission_id' => $commission->id,
                    'title' => $commission->title,
                    'total_product' => $subProductCount,
                    'total_bonus' => $subBonus,
                ];

                $subAgentsSummary[$sub->id]['total_bonus'] += $subBonus;

                $subTotalProduct += $subProductCount;
                $subTotalBonus += $subBonus;
            }

            // combined totals (agent + sub-agents)
            $combinedProduct = $agentProductCount + $subTotalProduct;
            $combinedBonus = $agentBonus + $subTotalBonus;

            // add aggregated row per commission (no name)
            $rows[] = [
                'title' => $commission->title,
                'total_product' => $combinedProduct,
                'total_bonus' => $combinedBonus,
            ];

            $agentTotalBonus += $agentBonus;
        }

        return [
            'rows' => $rows,
        ];
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
