<?php

namespace App\Http\Controllers\Admin;


use App\Models\Distribution;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Contact;

class ExportDeliveryOrderController extends Controller
{
    public function getDeliveryOrder(Distribution $distribution)
    {
        $data = [
            'title' => 'Surat Jalan',
            'distribution' => $distribution,
            'contact' => Contact::first()
        ];

        $pdf = Pdf::loadView('cms.admin.distributions.export.distribution-order', $data);
        return $pdf->stream('distribution-order-'. $distribution->distribution_number .'.pdf');
    }
}
