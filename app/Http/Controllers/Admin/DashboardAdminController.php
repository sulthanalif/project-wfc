<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        $data = [
            'totalAgent' => ($agents ? $agents : '-'),
            'totalProduct' => ($products ? $products : '-')
        ];

        return view('cms.admin.index', compact('data'));
    }
}
