<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Header;
use App\Models\Package;
use App\Models\Product;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class LandingpageController extends Controller
{
    public function index()
    {
        $products = Product::paginate(5);

        $header = Header::first();
        $profile = Profile::first();
        $contact = Contact::first();

        return view('landing.welcome', compact(
            'products',
            'header',
            'profile',
            'contact'
        ));
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
