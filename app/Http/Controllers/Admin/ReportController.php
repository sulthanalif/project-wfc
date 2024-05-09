<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function totalDeposit(Request $request)
    {
        $agents = User::role('agent')->get();
        $datas = [];
            foreach ($agents as $agent) {
                $totalPriceOrder = 0;
                $totalDeposit = 0;
                // $totalRemainingPayment = 0;

                //total price order
                foreach ($agent->order as $order) {
                    $totalPriceOrder += $order->total_price;

                    //total deposit
                    foreach ($order->payment as $payment) {
                        $totalDeposit += $payment->pay;
                    }

                    // $totalRemainingPayment += $order->payment->sortByDesc('created_at')->first()->remaining_payment;
                }


                $datas[] = [
                    'agent_name' => $agent->agentProfile->name,
                    'total_price_order' => $totalPriceOrder,
                    'total_deposit' => $totalDeposit,
                    'total_remaining_payment' => $totalPriceOrder - $totalDeposit
                ];
            }

        $paginationData = PaginationHelper::paginate($datas, 10, 'productDetail');

        // return view('cms.admin.reports.total-deposit', compact('paginationData'));
        return response()->json(compact('paginationData'));
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

            $datas [] = [
                'agent_name' => $agent->agentProfile->name,
                'total_product' => $totalProduct,
                'total_price' => $totalPrice
            ];
        }

        $paginationData = PaginationHelper::paginate($datas, 10, 'productDetail');

        return $paginationData;
    }

    public function instalment(Request $request)
    {
        $agents = User::role('agent')->get();
        $datas = [];
            foreach ($agents as $agent) {
                $totalPriceOrder = 0;
                // $totalDeposit = 0;
                // $totalRemainingPayment = 0;

                //total price order
                foreach ($agent->order as $order) {
                    $totalPriceOrder += $order->total_price;

                    //total deposit
                    // foreach ($order->payment as $payment) {
                    //     $totalDeposit += $payment->pay;
                    // }

                    // $totalRemainingPayment += $order->payment->sortByDesc('created_at')->first()->remaining_payment;
                }


                $datas[] = [
                    'agent_name' => $agent->agentProfile->name,
                    'total_deposit' => $totalPriceOrder,
                    // 'total_deposit' => $totalDeposit,
                    // 'total_remaining_payment' => $totalPriceOrder - $totalDeposit
                ];
            }

        $paginationData = PaginationHelper::paginate($datas, 10, 'productDetail');

        return $paginationData;
    }


}
