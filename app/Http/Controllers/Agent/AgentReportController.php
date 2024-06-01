<?php

namespace App\Http\Controllers\Agent;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AgentReportController extends Controller
{
    public function agentProduct()
    {

    }

    public function agentInstallment()
    {
        $payments = Payment::whereHas('order', function ($query) {
            $query->where('agent_id', auth()->id());
        })->orderBy('created_at', 'desc')->get();
        $stats = [];
        $pay = 0;
        $remaining_pay = 0;

        if (!$payments) {
            return response()->json('Data Belum Ada');
        }

        foreach ($payments as $payment) {
            $pay += $payment->pay;
            $remaining_pay += $payment->order->payment_status == 'paid' ? 0 : $payment->remaining_payment;
        }

        $stats = [
            'pay' => $pay,
            'remaining_pay' => $remaining_pay
        ];

        // if ($request->get('export') == 1) {
        //     return Excel::download(new ReportInstalmentExport($payments, $stats), 'Laporan_Rincian_Cicilan_' . now()->format('dmY') . '.xlsx');
        // }

        // return view('cms.admin.reports.instalment', compact('stats', 'payments'));
        return response()->json(compact('stats', 'payments'));
        // routenya 'report/instalment'
    }
}
