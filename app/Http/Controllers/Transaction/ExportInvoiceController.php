<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\BankOwner;
use App\Models\Order;
use App\Models\Payment;

class ExportInvoiceController extends Controller
{
    public function getInvoice(Order $order, Payment $payment, BankOwner $banks)
    {
        $oldPayments = $order->payment()->where('created_at', '<', $payment->created_at)->get();
        // return response()->json(compact('oldPayments'));
        $data = [
            'number' => $oldPayments->count() + 1,
            'title'=> 'Faktur Pembayaran Angsuran',
            'order' => $order,
            'payment' => $payment,
            'banks' => $banks,
            'installments' => $oldPayments->sum('pay') + $payment->pay,
            'remaining' => $order->total_price - ($oldPayments->sum('pay') + $payment->pay),
        ];

        // return response()->json($data);

        $pdf = Pdf::loadView('cms.transactions.export.invoice', $data);
        return $pdf->stream('invoice-'. $payment->invoice_number .'.pdf');
    }

    public function getInvoiceOrder(Order $order)
    {
        $data = [
            'title'=> 'Invoice',
            'order' => $order
        ];
        $pdf = Pdf::loadView('cms.transactions.export.invoice-order', $data);
        return $pdf->stream('invoice-order.pdf');
    }

    public function cekView(Order $order, Payment $payment, BankOwner $banks)
    {
        $data = [
            'title'=> 'Faktur Pembayaran Angsuran',
            'order' => $order,
            'payment' => $payment,
            'banks' => $banks
        ];
        return view('cms.transactions.export.invoice', $data);
    }
}
