<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardAdminController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $agents = User::role('agent')->count();
        $products = Product::all()->count();
        $orders = Order::where('status', 'pending')->count();
        $ordersDetail = Order::with('detail')->get();
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

        return view('cms.admin.index', compact('data'));
    }
}
