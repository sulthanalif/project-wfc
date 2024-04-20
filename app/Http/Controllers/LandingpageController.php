<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class LandingpageController extends Controller
{
    public function index()
    {
        $products = Product::paginate(5);

        return view('landing.welcome', compact('products'));
    }

    public function profile()
    {
        $users = User::all();
        $agentUsers = [];

        foreach ($users as $user) {
            if ($user->roles->first()->name == 'agent') {
                $agentUsers[] = $user;
            }
        }

        return view('landing.pages.profile', compact('agentUsers'));
    }

    public function catalogs()
    {
        $products = Product::all();

        return view('landing.pages.catalogs', compact('products'));
    }
}
