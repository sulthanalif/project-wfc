<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\Order;

class DetailOrderExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }

    public function __construct(string $order_id)
    {
        $this->order_id = $order_id;
    }

    public function view(): View
    {
        $order = Order::with('detail.product')->find($this->order_id);

        return view('exports.detail_order', [
            'order' => $order
        ]);
    }
}
