<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SubProduct;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportInstalmentExport;
use App\Exports\ReportRequirementExport;
use App\Exports\ReportTotalDepositExport;
use App\Exports\ReportProductDetailExport;

class ReportController extends Controller
{
    public function totalDeposit(Request $request)
    {
        $getAgent = $request->get('agent');

        $agentsName = User::role('agent')->whereHas('agentProfile', function($q) {
            $q->where('name', '!=', null)
            ->orderBy('name', 'asc');
        })->where('active', 1)->get();

        $agents = User::role('agent')->whereHas('agentProfile', function($q) use ($getAgent) {
            $q->where('name', 'like', '%'.$getAgent.'%');
        })->get();

        $datas = [];

        foreach ($agents as $agent) {
            $totalPriceOrder = 0;
            $totalDeposit = 0;

            //total price order
            foreach ($agent->order as $order) {
                if ($order->status == 'accepted') {
                    $totalPriceOrder += $order->total_price;

                    //total deposit
                    foreach ($order->payment as $payment) {
                        $totalDeposit += $payment->pay;
                    }
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
        }
        if (is_array($datas)) {
            $totalPriceOrderAll = array_sum(array_column($datas, 'total_price_order'));
            $totalDepositAll = array_sum(array_column($datas, 'total_deposit'));
            $totalRemainingAll = array_sum(array_column($datas, 'total_remaining_payment'));
        }

        $stats = [
            'totalPriceOrderAll' => $totalPriceOrderAll,
            'totalDepositAll' => $totalDepositAll,
            'totalRemainingAll' => $totalRemainingAll
        ];

        // $paginationData = PaginationHelper::paginate($datas, 10, 'totalDeposit');


        // untuk export, routenya harus "route('totalDeposit', ['export' => 1])"
        if ($request->get('export') == 1) {
            return Excel::download(new ReportTotalDepositExport($datas, $stats), 'Laporan_Total_Setoran_' . now()->format('dmY') . '.xlsx');
        }

        // return response()->json($request->get('agent'));
        return view('cms.admin.reports.total-deposit', compact('stats', 'datas', 'agentsName'));
        // return response()->json(compact('stats', 'paginationData'));
        // routenya 'report/total-deposit'

    }

    public function productDetail(Request $request)
    {

        $getAgent = $request->get('agent');

        $agentsName = User::role('agent')->whereHas('agentProfile', function($q) {
            $q->where('name', '!=', null)
            ->orderBy('name', 'asc');
        })->where('active', 1)->get();

        $agents = User::role('agent')->whereHas('agentProfile', function ($q) use ($getAgent) {
            $q->where('name', 'like', '%'.$getAgent.'%');
        })->get();

        $datas = [];

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
                $datas[] = [
                    'agent_name' => $agent->agentProfile->name,
                    'total_product' => $totalProduct,
                    'total_price' => $totalPrice
                ];
            }
        }
        if (is_array($datas)) {
            $totalProductAll = array_sum(array_column($datas, 'total_product'));
            $totalPriceAll = array_sum(array_column($datas, 'total_price'));
        }

        $stats = [
            'totalProductAll' => $totalProductAll,
            'totalPriceAll' => $totalPriceAll,
        ];

        // untuk export, routenya harus "route('productDetail', ['export' => 1])"
        if ($request->get('export') == 1) {
            return Excel::download(new ReportProductDetailExport($datas, $stats), 'Laporan_Rincian_Perpaket_' . now()->format('dmY') . '.xlsx');
        }

        // $paginationData = PaginationHelper::paginate($datas, 10, 'productDetail');

        return view('cms.admin.reports.product-detail', compact('stats', 'datas', 'agentsName'));
        // return response()->json(compact('stats', 'paginationData'));
        // routenya 'report/product-detail'
    }

    public function instalment(Request $request)
    {
        $filterAgent = $request->get('agent');
        $filterDate = $request->get('date');

        $agentsName = Order::whereHas('payment', function($q) {
            $q->where('pay', '>', 0);
        })->pluck('agent_id')->unique()->map(function ($agentId) {
            return User::find($agentId)->agentProfile->name;
        })->toArray();

        // return response()->json($agentsName);
        $payments = Payment::with('order')->orderBy('created_at', 'desc')->wherehas('order.agent.agentProfile', function ($q) use ($filterAgent, $filterDate) {
            if ($filterAgent && $filterDate) {
                $q->where('name', 'like', '%'.$filterAgent.'%')
                    ->whereDate('date', $filterDate);
            } elseif ($filterAgent && !$filterDate) {
                $q->where('name', 'like', '%'.$filterAgent.'%');
            } elseif (!$filterAgent && $filterDate) {
                $q->whereDate('date', $filterDate);
            }
        })->get();

        $stats = [];
        $pay = 0;
        $remaining_pay = 0;

        foreach ($payments as $payment) {
            $pay += $payment->pay;
            $remaining_pay += $payment->order->payment_status == 'paid' ? 0 : $payment->remaining_payment;
        }

        $stats = [
            'pay' => $pay,
            'remaining_pay' => $remaining_pay
        ];

        if ($request->get('export') == 1) {
            return Excel::download(new ReportInstalmentExport($payments, $stats), 'Laporan_Rincian_Cicilan_' . now()->format('dmY') . '.xlsx');
        }

        return view('cms.admin.reports.instalment', compact('stats', 'payments', 'agentsName'));
        // return response()->json(compact('stats', 'payments'));
        // routenya 'report/instalment'
    }


    public function requirement(Request $request)
    {
        $orders = Order::get();
        $datas = [];
        $datasubs = [];
        foreach ($orders as $item) {
            foreach ($item->detail as $detail) {
                $found = false;
                foreach ($datas as &$data) {
                    if ($data['product_id'] == $detail->product_id) {
                        $data['qty'] += $detail->qty;
                        $data['price'] += ($detail->product->price * $detail->product->days) * $data['qty'];
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $datas[] = [
                        'product_id' => $detail->product_id,
                        'product_name' => $detail->product ? $detail->product->name : '',
                        'qty' => $detail->qty,
                        'price' => ($detail->product ? $detail->product->price * $detail->product->days : 0) * $detail->qty
                    ];
                }

                if ($detail->product->subProduct) {
                    foreach ($detail->product->subProduct as $sub) {
                        $found1 = false;
                        foreach ($datasubs as &$data1) {
                            if ($data1['id'] == $sub->subProduct->id) {
                                $data1['qty'] += $detail->qty * $sub->amount;
                                $data1['price'] += ($detail->qty * $sub->amount) * $sub->subProduct->price;
                                $found1 = true;
                                break;
                            }
                        }
                        if (!$found1) {
                            $datasubs[] = [
                                'id' => $sub->subProduct->id,
                                'name' => $sub->subProduct->name,
                                'qty' => $detail->qty * $sub->amount,
                                'unit' => $sub->subProduct->unit,
                                'price' => ($detail->qty * $sub->amount) * $sub->subProduct->price
                            ];
                        }
                    }
                }
            }
        }

        if (is_array($datasubs)) {
            $totalSubProductAll = array_sum(array_column($datasubs, 'qty'));
            $totalPriceAll = array_sum(array_column($datasubs, 'price'));
        }

        $stats = [
            'totalSubProductAll' => $totalSubProductAll,
            'totalPriceAll' => $totalPriceAll,
        ];

        // untuk export, routenya harus "route('requirement', ['export' => 1])"
        if ($request->get('export') == 1) {
            return Excel::download(new ReportRequirementExport($datasubs, $stats), 'Laporan_Rincian_SubProduct_' . now()->format('dmY') . '.xlsx');
        }

        // $paginationData = PaginationHelper::paginate($datasubs, 10, 'requirement');

        return view('cms.admin.reports.requirement', compact('datasubs', 'stats', 'datas'));
        // return response()->json(compact('datas', 'datasubs'));
    }
}
