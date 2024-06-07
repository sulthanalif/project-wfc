<?php

namespace App\Http\Controllers\Admin;


use App\Models\Distribution;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class ExportDeliveryOrderController extends Controller
{
    public function getDeliveryOrder(Distribution $distribution)
    {
        $data = [
            'title' => 'Surat Jalan',
            'distribution' => $distribution
        ];

        $pdf = Pdf::loadView('cms.admin.distribution.export.distribution-order', $data);
        return $pdf->stream('distribution-order-'. $distribution->distribution_number .'.pdf');
    }
}
