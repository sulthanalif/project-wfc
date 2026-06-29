<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportDatas;

class DashboardAdminController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index(Request $request)
    {
        $agents = User::role('agent')->count();
        $products = Product::whereHas('package.package.period', function ($query) {
                $query->where('is_active', 1);
            })->count();
        $orders = Order::where('status', 'pending')->count();
        $ordersDetail = Order::where('status', 'accepted')->whereHas('detail.product.package.package.period', function ($query) {
            $query->where('is_active', 1);
        })->get();
        $productSales = 0;
        foreach ($ordersDetail as $order) {
            foreach ($order->detail as $detail) {
                $productSales += $detail->qty;
            }
        }

        $data = [
            'totalAgent' => ($agents ? $agents : '-'),
            'totalProduct' => ($products ? $products : '-'),
            'newOrder' => ($orders ? $orders : '-'),
            'productSales' => ($productSales ? $productSales : '-'),
        ];

        $orderPages = $request->get('orderPage') ?? 10;

        if ($orderPages == 'all') {
                $orders = Order::with('agent.agentProfile')->where('status', ['accepted', 'stop'])->get()->groupBy('agent_id');
            } else {
                $orderPage = intval($orderPages);
                $orders = Order::with('agent.agentProfile')->where('status', ['accepted', 'stop'])->paginate($orderPage)->groupBy('agent_id');
            }

            if ($request->get('export') == 'true') {
                $datas = Order::with('agent.agentProfile')->where('status', ['accepted', 'stop'])->get()->groupBy('agent_id')->map(function ($item) {
                    $allPaid = $item->every(function ($o) {
                        return $o->payment_status === 'paid';
                    });
                    $hasPending = $item->contains(function ($o) {
                        return $o->payment_status === 'pending';
                    });
                    return [
                        'agent' => $item->first()->agent->agentProfile->name,
                        'total_price' => $item->sum('total_price'),
                        'status' => $allPaid ? 'Lunas' : ($hasPending ? 'Dicicilkan' : 'Belum Lunas')
                    ];
                });

                $headers = [
                    'agent',
                    'total_price',
                    'status',
                ];
                // return response()->json($datas);
                return Excel::download(new ExportDatas($datas, 'Data Pembayaran', $headers), 'Data Pembayaran.xlsx');
            }

        return view('cms.admin.index', compact('data', 'orders'));
    }
}
