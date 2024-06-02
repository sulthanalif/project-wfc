<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;

class ExportInvoiceController extends Controller
{
    public function getInvoice(Order $order, Payment $payment)
    {
        $data = [
            'title'=> 'Faktur Pembayaran Angsuran',
            'order' => $order,
            'payment' => $payment
        ];


        $pdf = Pdf::loadView('cms.transactions.export.invoice', $data);
        return $pdf->stream('invoice.pdf');
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

    public function cekView(Order $order, Payment $payment)
    {
        $data = [
            'title'=> 'Faktur Pembayaran Angsuran',
            'order' => $order,
            'payment' => $payment
        ];
        return view('cms.transactions.export.invoice', $data);
    }
}
