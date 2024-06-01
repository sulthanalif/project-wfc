<?php

namespace App\Http\Controllers\Agent;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $myOrders = Order::where('agent_id', auth()->user()->id)->count();
        return view('cms.agen.index', compact('myOrders'));
    }

    public function noActive()
    {
        return view('cms.agen.noactive');
    }
}
