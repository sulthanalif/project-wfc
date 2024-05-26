<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportInstalmentExport;
use App\Exports\ReportTotalDepositExport;
use App\Exports\ReportProductDetailExport;

class ReportController extends Controller
{
    public function totalDeposit(Request $request)
    {
        $agents = User::role('agent')->get();
        $datas = [];

            foreach ($agents as $agent) {
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

        $paginationData = PaginationHelper::paginate($datas, 10, 'totalDeposit');


        // untuk export, routenya harus "route('totalDeposit', ['export' => 1])"
        if ($request->get('export') == 1) {
            return Excel::download(new ReportTotalDepositExport($datas, $stats), 'Laporan_Total_Setoran_'.now()->format('dmY').'.xlsx');
        }

        return view('cms.admin.reports.total-deposit', compact('stats', 'paginationData'));
        // return response()->json(compact('stats', 'paginationData'));
        // routenya 'report/total-deposit'

    }

    public function productDetail(Request $request)
    {
        $agents = User::role('agent')->get();
        $datas = [];

        foreach ($agents as $agent) {
            $totalProduct = 0;
            $totalPrice = 0;

            foreach ($agent->order as $order) {
                $totalPrice += $order->total_price;

                foreach ($order->detail as $detail) {
                    $totalProduct += $detail->qty;
                }
            }

            if ($totalPrice > 0) {
                $datas [] = [
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
            return Excel::download(new ReportProductDetailExport($datas, $stats), 'Laporan_Rincian_Perpaket_'.now()->format('dmY').'.xlsx');
        }

        $paginationData = PaginationHelper::paginate($datas, 10, 'productDetail');

        return view('cms.admin.reports.product-detail', compact('stats', 'paginationData'));
        // return response()->json(compact('stats', 'paginationData'));
        // routenya 'report/product-detail'
    }

    public function instalment(Request $request)
    {
        $payments = Payment::with('order')->orderBy('created_at', 'desc')->get();
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
            return Excel::download(new ReportInstalmentExport($payments, $stats), 'Laporan_Rincian_Cicilan_'.now()->format('dmY').'.xlsx');
        }

        return view('cms.admin.reports.instalment', compact('stats', 'payments'));
        // return response()->json(compact('stats', 'payments'));
        // routenya 'report/instalment'
    }




}
