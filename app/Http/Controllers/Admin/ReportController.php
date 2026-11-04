<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Reward;
use App\Models\Payment;
use App\Models\Spending;
use App\Models\SubProduct;
use App\Models\OrderDetail;
use App\Models\Distribution;
use App\Models\SpendingType;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportAgentOrderExport;
use App\Exports\ReportInstalmentExport;
use App\Exports\ReportRequirementExport;
use App\Exports\ReportTotalDepositExport;
use App\Exports\ReportProductDetailExport;
use App\Exports\ReportStockSubExport;
use App\Models\Product;

class ReportController extends Controller
{
    protected function paginateData($items, int $perPage, Request $request): LengthAwarePaginator
    {
        $page = (int) $request->get('page', 1);
        $collection = $items instanceof \Illuminate\Support\Collection ? $items : collect($items);
        $total = $collection->count();
        $sliced = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $sliced,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    }

    public function totalDeposit(Request $request)
    {
        $getAgent = $request->get('agent');
        $perPage = min(max(intval($request->get('perPage', 10)), 1), 100);

        $agentsName = User::role('agent')->whereHas('agentProfile', function ($q) {
            $q->whereNotNull('name')
                ->orderBy('name', 'asc');
        })->where('active', 1)->get();

        $agentsQuery = User::role('agent')
            ->whereHas('order', function ($q) {
                $q->where('status', 'accepted');
            })
            ->whereHas('agentProfile', function ($q) use ($getAgent) {
                if ($getAgent && $getAgent !== 'all') {
                    $q->where('name', 'like', '%' . $getAgent . '%');
                }
            })
            ->with('agentProfile')
            ->select('users.*')
            ->selectSub(
                Order::selectRaw('coalesce(sum(total_price), 0)')
                    ->whereColumn('orders.agent_id', 'users.id')
                    ->where('orders.status', 'accepted'),
                'total_price_order'
            )
            ->selectSub(
                Payment::selectRaw('coalesce(sum(payments.pay), 0)')
                    ->join('orders', 'payments.order_id', '=', 'orders.id')
                    ->whereColumn('orders.agent_id', 'users.id')
                    ->where('orders.status', 'accepted')
                    ->where('payments.status', 'accepted'),
                'total_deposit'
            );

        $orderStatsQuery = Order::where('status', 'accepted');
        if ($getAgent && $getAgent !== 'all') {
            $orderStatsQuery->whereHas('agent.agentProfile', fn($q) => $q->where('name', 'like', '%' . $getAgent . '%'));
        }
        $totalPriceOrderAll = (float) $orderStatsQuery->sum('total_price');

        $paymentStatsQuery = Payment::where('status', 'accepted')->whereHas('order', function ($q) use ($getAgent) {
            $q->where('status', 'accepted');
            if ($getAgent && $getAgent !== 'all') {
                $q->whereHas('agent.agentProfile', fn($ap) => $ap->where('name', 'like', '%' . $getAgent . '%'));
            }
        });
        $totalDepositAll = (float) $paymentStatsQuery->sum('pay');
        $totalRemainingAll = $totalPriceOrderAll - $totalDepositAll;

        $stats = [
            'totalPriceOrderAll' => $totalPriceOrderAll,
            'totalDepositAll' => $totalDepositAll,
            'totalRemainingAll' => $totalRemainingAll,
        ];

        if ($request->get('export') == 1) {
            $datas = $agentsQuery->get()->map(function ($agent) {
                return [
                    'agent_name' => $agent->agentProfile->name ?? '-',
                    'total_price_order' => (int) $agent->total_price_order,
                    'total_deposit' => (int) $agent->total_deposit,
                    'total_remaining_payment' => (int) ($agent->total_price_order - $agent->total_deposit),
                ];
            });
            return Excel::download(new ReportTotalDepositExport($datas, $stats), 'Laporan_Total_Setoran_' . now()->format('dmY') . '.xlsx');
        }

        $datas = $agentsQuery->paginate($perPage)->withQueryString()->through(function ($agent) {
            return [
                'agent_name' => $agent->agentProfile->name ?? '-',
                'total_price_order' => (int) $agent->total_price_order,
                'total_deposit' => (int) $agent->total_deposit,
                'total_remaining_payment' => (int) ($agent->total_price_order - $agent->total_deposit),
            ];
        });

        return view('cms.admin.reports.total-deposit', compact('stats', 'datas', 'agentsName'));
    }

    public function productDetail(Request $request)
    {
        $perPage = min(max(intval($request->get('perPage', 10)), 1), 100);

        $productDetailsQuery = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
            ->leftJoin('products', 'order_details.product_id', '=', 'products.id')
            ->where('orders.status', 'accepted')
            ->select([
                'order_details.product_id',
                'products.name as product_name',
                'products.is_safe_point',
                DB::raw('sum(order_details.qty) as total_product'),
            ])
            ->groupBy('order_details.product_id', 'products.name', 'products.is_safe_point')
            ->orderBy('products.name', 'asc');

        $totalProductAll = (int) OrderDetail::whereHas('order', fn($q) => $q->where('status', 'accepted'))->sum('qty');
        $totalPriceAll = (float) Order::where('status', 'accepted')->sum('total_price');

        $stats = [
            'totalProductAll' => $totalProductAll,
            'totalPriceAll' => $totalPriceAll,
        ];

        if ($request->get('export') == 1) {
            $datas = $productDetailsQuery->get()->map(function ($item) {
                $name = $item->product_name ?? 'Paket Tidak Tersedia';
                if ($item->is_safe_point == 1) {
                    $name .= ' (Titik Aman)';
                }
                return [
                    'product_id' => $item->product_id,
                    'package' => $name,
                    'total_product' => (int) $item->total_product,
                ];
            });
            return Excel::download(new ReportProductDetailExport($datas, $stats), 'Laporan_Rincian_Perpaket_' . now()->format('dmY') . '.xlsx');
        }

        $datas = $productDetailsQuery->paginate($perPage)->withQueryString()->through(function ($item) {
            $name = $item->product_name ?? 'Paket Tidak Tersedia';
            if ($item->is_safe_point == 1) {
                $name .= ' (Titik Aman)';
            }
            return [
                'product_id' => $item->product_id,
                'package' => $name,
                'total_product' => (int) $item->total_product,
            ];
        });

        return view('cms.admin.reports.product-detail', compact('stats', 'datas'));
    }

    public function agentProductDetail(Request $request, $productId)
    {
        $perPage = min(max(intval($request->get('perPage', 10)), 1), 100);
        $product = Product::where('id', $productId)->with(['order.order'])->get();

        $ordersQuery = User::role('agent')
            ->whereHas('order.detail', function ($q) use ($productId) {
                $q->where('product_id', $productId)
                    ->whereHas('order', fn($oq) => $oq->where('status', 'accepted'));
            })
            ->with('agentProfile')
            ->select('users.*')
            ->selectSub(
                OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
                    ->whereColumn('orders.agent_id', 'users.id')
                    ->where('orders.status', 'accepted')
                    ->where('order_details.product_id', $productId)
                    ->selectRaw('coalesce(sum(order_details.qty), 0)'),
                'totalProduct'
            );

        $totalProductCount = (int) OrderDetail::whereHas('order', fn($q) => $q->where('status', 'accepted'))
            ->where('product_id', $productId)
            ->sum('qty');

        $orders = $ordersQuery->paginate($perPage)->withQueryString()->through(function ($user) {
            return (object) [
                'agent' => $user,
                'totalProduct' => (int) $user->totalProduct,
            ];
        });

        return view('cms.admin.reports.partial.agent-product-detail', compact('product', 'orders', 'totalProductCount'));
    }

    public function agentOrder(Request $request)
    {
        $getAgent = $request->get('agent');
        $perPage = min(max(intval($request->get('perPage', 10)), 1), 100);

        $agentsName = User::role('agent')->whereHas('agentProfile', function ($q) {
            $q->whereNotNull('name')
                ->orderBy('name', 'asc');
        })->where('active', 1)->get();

        $agentsQuery = User::role('agent')
            ->whereHas('order', function ($q) {
                $q->where('status', 'accepted');
            })
            ->whereHas('agentProfile', function ($q) use ($getAgent) {
                if ($getAgent && $getAgent !== 'all') {
                    $q->where('name', 'like', '%' . $getAgent . '%');
                }
            })
            ->with('agentProfile')
            ->select('users.*')
            ->selectSub(
                Order::selectRaw('coalesce(sum(total_price), 0)')
                    ->whereColumn('orders.agent_id', 'users.id')
                    ->where('orders.status', 'accepted'),
                'total_price'
            )
            ->selectSub(
                OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
                    ->whereColumn('orders.agent_id', 'users.id')
                    ->where('orders.status', 'accepted')
                    ->selectRaw('coalesce(sum(order_details.qty), 0)'),
                'total_product'
            );

        $orderStatsQuery = Order::where('status', 'accepted');
        if ($getAgent && $getAgent !== 'all') {
            $orderStatsQuery->whereHas('agent.agentProfile', fn($q) => $q->where('name', 'like', '%' . $getAgent . '%'));
        }
        $totalPriceAll = (float) $orderStatsQuery->sum('total_price');

        $detailStatsQuery = OrderDetail::whereHas('order', function ($q) use ($getAgent) {
            $q->where('status', 'accepted');
            if ($getAgent && $getAgent !== 'all') {
                $q->whereHas('agent.agentProfile', fn($ap) => $ap->where('name', 'like', '%' . $getAgent . '%'));
            }
        });
        $totalProductAll = (int) $detailStatsQuery->sum('qty');

        $stats = [
            'totalProductAll' => $totalProductAll,
            'totalPriceAll' => $totalPriceAll,
        ];

        if ($request->get('export') == 1) {
            $datas = $agentsQuery->get()->map(function ($agent) {
                return [
                    'agent_name' => $agent->agentProfile->name ?? '-',
                    'total_product' => (int) $agent->total_product,
                    'total_price' => (float) $agent->total_price,
                ];
            });
            return Excel::download(new ReportAgentOrderExport($datas, $stats), 'Laporan_Rincian_Order_Agent_' . now()->format('dmY') . '.xlsx');
        }

        $datas = $agentsQuery->paginate($perPage)->withQueryString()->through(function ($agent) {
            return [
                'agent_name' => $agent->agentProfile->name ?? '-',
                'total_product' => (int) $agent->total_product,
                'total_price' => (float) $agent->total_price,
            ];
        });

        return view('cms.admin.reports.agent-order', compact('stats', 'datas', 'agentsName'));
    }

    public function agentReward(Request $request)
    {
        $getAgent = $request->get('agent');
        $perPage = min(max(intval($request->get('perPage', 10)), 1), 100);

        // 1. Ambil semua reward yang aktif dan urutkan dari target terbesar
        $activeRewards = Reward::whereHas('period', function ($query) {
            $query->where('start_date', '<=', now())->where('end_date', '>=', now());
        })->orderBy('target_qty', 'desc')->get();

        $agentsName = User::role('agent')->whereHas('agentProfile', function ($q) {
            $q->whereNotNull('name')
                ->orderBy('name', 'asc');
        })->where('active', 1)->get();

        $agentsQuery = User::role('agent')
            ->whereHas('agentProfile', function ($q) use ($getAgent) {
                if ($getAgent && $getAgent !== 'all') {
                    $q->where('name', 'like', '%' . $getAgent . '%');
                }
            })
            ->whereHas('order', function ($q) {
                $q->where('status', 'accepted');
            })
            ->with('agentProfile')
            ->select('users.*')
            ->selectSub(
                OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
                    ->whereColumn('orders.agent_id', 'users.id')
                    ->where('orders.status', 'accepted')
                    ->selectRaw('coalesce(sum(order_details.qty), 0)'),
                'total_product'
            );

        $agents = $agentsQuery->get();
        $datas = [];

        foreach ($agents as $agent) {
            $totalProduct = (int) $agent->total_product;
            $agentRewardTitle = null;

            foreach ($activeRewards as $reward) {
                if ($totalProduct >= $reward->target_qty) {
                    $agentRewardTitle = $reward->title;
                    break;
                }
            }

            if ($agentRewardTitle !== null) {
                $datas[] = [
                    'agent_name' => $agent->agentProfile->name ?? '-',
                    'total_product' => $totalProduct,
                    'reward' => $agentRewardTitle,
                    'total_price' => $agentRewardTitle,
                ];
            }
        }

        if (!empty($datas)) {
            array_multisort(array_column($datas, 'total_product'), SORT_DESC, $datas);
        }

        $totalProductAll = !empty($datas) ? array_sum(array_column($datas, 'total_product')) : 0;
        $stats = [
            'totalProductAll' => $totalProductAll,
            'totalPriceAll' => 0,
        ];

        if ($request->get('export') == 1) {
            return Excel::download(new ReportAgentOrderExport($datas, $stats), 'Laporan_Reward_Agent_' . now()->format('dmY') . '.xlsx');
        }

        $datas = $this->paginateData($datas, $perPage, $request);

        return view('cms.admin.reports.agent-reward', compact('datas'));
    }

    public function instalment(Request $request)
    {
        $filterAgent = $request->get('agent');
        $filterDate = $request->get('date');
        $filterVerify = $request->get('verify');
        $perPage = min(max(intval($request->get('perPage', 10)), 1), 100);

        $agentsName = User::role('agent')
            ->whereHas('agentProfile')
            ->whereHas('order.payment', fn($q) => $q->where('pay', '>', 0))
            ->with('agentProfile')
            ->get()
            ->pluck('agentProfile.name')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        $paymentsQuery = Payment::with(['order.agent.agentProfile', 'order'])
            ->where('status', 'accepted')
            ->orderBy('created_at', 'desc')
            ->whereHas('order.agent.agentProfile', function ($q) use ($filterAgent) {
                if ($filterAgent) {
                    $q->where('name', 'like', '%' . $filterAgent . '%');
                }
            })
            ->when($filterDate, function ($q) use ($filterDate) {
                $q->whereDate('date', $filterDate);
            })
            ->when(isset($filterVerify), function ($query) use ($filterVerify) {
                $query->where('is_confirmed', (bool)$filterVerify);
            });

        $totalPrice = (float) Order::where('status', 'accepted')->sum('total_price');
        $pay = (float) (clone $paymentsQuery)->sum('pay');
        $remaining_pay = max(0, $totalPrice - $pay);

        $stats = [
            'pay' => $pay,
            'remaining_pay' => $remaining_pay,
        ];

        if ($request->get('export') == 1) {
            return Excel::download(new ReportInstalmentExport($paymentsQuery->get(), $stats), 'Laporan_Rincian_Cicilan_' . now()->format('dmY') . '.xlsx');
        }

        $payments = $paymentsQuery->paginate($perPage)->withQueryString();

        return view('cms.admin.reports.instalment', compact('stats', 'payments', 'agentsName'));
    }

    public function requirement(Request $request)
    {
        $perPage = min(max(intval($request->get('perPage', 10)), 1), 100);

        $datasubsQuery = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('product_sub_products', 'order_details.product_id', '=', 'product_sub_products.product_id')
            ->join('sub_products', 'product_sub_products.sub_product_id', '=', 'sub_products.id')
            ->where('orders.status', 'accepted')
            ->select([
                'sub_products.id',
                'sub_products.name',
                'sub_products.unit',
                DB::raw('sum(order_details.qty * product_sub_products.amount) as qty'),
                DB::raw('sum(order_details.qty * product_sub_products.amount * sub_products.price) as price'),
            ])
            ->groupBy('sub_products.id', 'sub_products.name', 'sub_products.unit')
            ->orderBy('sub_products.name', 'asc');

        $allDataSubs = $datasubsQuery->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'unit' => $item->unit,
                'qty' => (int) $item->qty,
                'price' => (float) $item->price,
            ];
        })->toArray();

        $totalSubProductAll = array_sum(array_column($allDataSubs, 'qty'));
        $totalPriceAll = array_sum(array_column($allDataSubs, 'price'));

        $stats = [
            'totalSubProductAll' => $totalSubProductAll,
            'totalPriceAll' => $totalPriceAll,
        ];

        if ($request->get('export') == 1) {
            return Excel::download(new ReportRequirementExport($allDataSubs, $stats), 'Laporan_Rincian_SubProduct_' . now()->format('dmY') . '.xlsx');
        }

        $datasubs = $this->paginateData($allDataSubs, $perPage, $request);

        return view('cms.admin.reports.requirement', [
            'datasubs' => $datasubs,
            'stats' => $stats,
            'datas' => [],
        ]);
    }

    public function daily(Request $request)
    {
        $feature = $request->get('feature') ?? 'orders';
        $isExport = $request->get('export') == 1;

        // --- ORDERS ---
        $agents = User::role('agent')->where('active', 1)->with(['agentProfile', 'order' => function ($q) {
            $q->whereDate('created_at', Carbon::today());
        }])->get();

        $agentOrders = [];

        foreach ($agents as $agent) {
            $totalProduct = 0;
            $totalPrice = 0;

            foreach ($agent->order as $order) {
                if ($order->status == 'accepted') {
                    $totalPrice += $order->total_price;
                    foreach ($order->detail as $detail) {
                        $totalProduct += $detail->qty;
                    }
                }
            }

            if ($totalPrice > 0) {
                $agentOrders[] = [
                    'agent_name' => $agent->agentProfile->name ?? '-',
                    'total_product' => $totalProduct,
                    'total_price' => $totalPrice
                ];
            }
        }

        $agentStats = [
            'totalProductAll' => array_sum(array_column($agentOrders, 'total_product')) ?? 0,
            'totalPriceAll' => array_sum(array_column($agentOrders, 'total_price')) ?? 0,
        ];

        if ($isExport && $feature === 'orders') {
            return Excel::download(new ReportAgentOrderExport($agentOrders, $agentStats), 'Laporan_Rincian_Order_Agent_' . now()->format('dmY') . '.xlsx');
        }

        // --- PRODUCT DETAIL ---
        $productOrders = Order::whereDate('created_at', Carbon::today())->get();
        $productDetails = [];

        foreach ($productOrders as $item) {
            if ($item->status == 'accepted') {
                foreach ($item->detail as $order) {
                    if ($order->product) {
                        $packageName = $order->product->name;
                        if (isset($productDetails[$packageName])) {
                            $productDetails[$packageName]['total_product'] += $order->qty;
                        } else {
                            $productDetails[$packageName] = [
                                'package' => $packageName,
                                'total_product' => $order->qty,
                            ];
                        }
                    } else {
                        $productDetails[] = [
                            'package' => 'Order #' . $order->order->order_number . ' - Paket Tidak Tersedia (' . $order->product_id . ')',
                            'total_product' => $order->qty,
                        ];
                    }
                }
            }
        }

        $totalProductAll = array_sum(array_column($productDetails, 'total_product')) ?? 0;
        // $totalPriceAll = array_sum(array_column($productDetails, 'total_price')) ?? 0;

        $productStats = [
            'totalProductAll' => $totalProductAll,
            // 'totalPriceAll' => $totalPriceAll,
        ];

        if ($isExport && $feature === 'package') {
            return Excel::download(new ReportProductDetailExport($productDetails, $productStats), 'Laporan_Rincian_Perpaket_' . now()->format('dmY') . '.xlsx');
        }

        // --- INSTALMENT ---
        $payments = Payment::with('order.agent.agentProfile')
            ->where('status', 'accepted')
            ->whereDate('created_at', Carbon::today())
            ->get();

        $pay = 0;
        $remainingPay = 0;

        foreach ($payments as $payment) {
            $pay += $payment->pay;
            $remainingPay += $payment->order && $payment->order->payment_status !== 'paid'
                ? $payment->remaining_payment : 0;
        }

        $instalmentStats = [
            'pay' => $pay,
            // 'remaining_pay' => $remainingPay
        ];

        if ($isExport && $feature === 'instalment') {
            return Excel::download(new ReportInstalmentExport($payments, $instalmentStats), 'Laporan_Rincian_Cicilan_' . now()->format('dmY') . '.xlsx');
        }

        // --- DISTRIBUTION ---
        $distributions = Distribution::with('order', 'detail')
            ->whereDate('date', Carbon::today())
            ->get();

        $distributionData = [];

        foreach ($distributions as $distribution) {
            $agentName = $distribution->order && $distribution->order->agent && $distribution->order->agent->agentProfile
                ? $distribution->order->agent->agentProfile->name : 'Agent Tidak Tersedia';
            $packageName = 'Paket Tidak Tersedia';
            if ($distribution->order && $distribution->order->detail) {
                foreach ($distribution->order->detail as $detail) {
                    if ($detail->product) {
                        $packageName = $detail->product->name;
                    }
                }
            }

            // Total pesanan paket (jumlah qty dari semua detail order untuk paket ini pada order terkait)
            $totalOrderQty = 0;
            if ($distribution->order && $distribution->order->detail) {
                foreach ($distribution->order->detail as $detail) {
                    if ($detail->product && $detail->product->name == $packageName) {
                        $totalOrderQty += $detail->qty;
                    }
                }
            }

            $totalDistributionQty = 0;
            if ($distribution->detail) {
                foreach ($distribution->detail as $distDetail) {
                    $totalDistributionQty += $distDetail->qty;
                }
            }

            $key = $agentName . '|' . $packageName;
            if (isset($distributionData[$key])) {
                $distributionData[$key]['total_distribution'] += $totalDistributionQty;
            } else {
                $distributionData[$key] = [
                    'agent_name' => $agentName,
                    'package' => $packageName,
                    'total_order' => $totalOrderQty,
                    'total_distribution' => $totalDistributionQty,
                ];
            }
        }
        // Re-index array numerically for view/export
        $distributionData = array_values($distributionData);
        $totalOrderAll = array_sum(array_column($distributionData, 'total_order')) ?? 0;
        $totalDistributionAll = array_sum(array_column($distributionData, 'total_distribution')) ?? 0;

        $distributionStats = [
            'totalOrderAll' => $totalOrderAll,
            'totalDistributionAll' => $totalDistributionAll,
        ];

        // if ($isExport && $feature === 'distribution') {
        //     return Excel::download(new ReportDistributionExport($distributionData, $distributionStats), 'Laporan_Rincian_Distribusi_' . now()->format('dmY') . '.xlsx');
        // }

        // AGENT NAMES for filtering
        // $agentsName = User::role('agent')->whereHas('agentProfile', function ($q) {
        //     $q->whereNotNull('name');
        // })->pluck('agentProfile.name')->toArray();

        return view('cms.admin.reports.daily', compact(
            'feature',
            'productDetails',
            'productStats',
            'agentOrders',
            'agentStats',
            'payments',
            'instalmentStats',
            'distributions',
            'distributionData',
            'distributionStats',
            // 'agentsName'
        ));
    }

    public function stockSubProduct(Request $request)
    {
        $perPage = min(max(intval($request->get('perPage', 10)), 1), 100);
        $spendingType = SpendingType::firstOrCreate(['name' => 'Pengadaan']);

        // 1. Kebutuhan sub-product dari pesanan accepted via SQL aggregation
        $neededQuery = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('product_sub_products', 'order_details.product_id', '=', 'product_sub_products.product_id')
            ->join('sub_products', 'product_sub_products.sub_product_id', '=', 'sub_products.id')
            ->where('orders.status', 'accepted')
            ->select([
                'sub_products.id',
                'sub_products.name',
                'sub_products.unit',
                DB::raw('sum(order_details.qty * product_sub_products.amount) as needed'),
            ])
            ->groupBy('sub_products.id', 'sub_products.name', 'sub_products.unit')
            ->orderBy('sub_products.name', 'asc');

        $neededSubProducts = $neededQuery->get();

        // 2. Sub-product yang didistribusikan (status delivered) via SQL aggregation
        $distributedSubProducts = DB::table('distribution_details')
            ->join('distributions', 'distribution_details.distribution_id', '=', 'distributions.id')
            ->join('order_details', 'distribution_details.order_detail_id', '=', 'order_details.id')
            ->join('product_sub_products', 'order_details.product_id', '=', 'product_sub_products.product_id')
            ->where('distributions.status', 'delivered')
            ->groupBy('product_sub_products.sub_product_id')
            ->select('product_sub_products.sub_product_id', DB::raw('sum(distribution_details.qty * product_sub_products.amount) as total_distributed'))
            ->pluck('total_distributed', 'sub_product_id')
            ->toArray();

        // 3. Pengadaan per sub-product via 1 SQL query
        $procurements = Spending::where('spending_type_id', $spendingType->id)
            ->where('information', 'like', 'Pengadaan: %')
            ->groupBy('information')
            ->select('information', DB::raw('sum(qty) as total_qty'))
            ->pluck('total_qty', 'information')
            ->toArray();

        $finalData = [];
        foreach ($neededSubProducts as $item) {
            $subProductId = $item->id;
            $subProductName = $item->name;
            $procured = (int) ($procurements['Pengadaan: ' . $subProductName] ?? 0);
            $distributed = (int) ($distributedSubProducts[$subProductId] ?? 0);
            $needed = (int) $item->needed;
            $stock = $procured - ($needed - $distributed);

            $finalData[] = [
                'id' => $subProductId,
                'name' => $subProductName,
                'unit' => $item->unit,
                'needed' => $needed,
                'procurement' => $procured,
                'distributed' => $distributed,
                'stock' => $stock,
            ];
        }

        if ($request->get('export') == 1) {
            return Excel::download(new ReportStockSubExport($finalData), 'Laporan_Stock_SubProduct_' . now()->format('dmY') . '.xlsx');
        }

        $products = $this->paginateData($finalData, $perPage, $request);

        return view('cms.admin.reports.stock-sub', compact('products'));
    }
}
