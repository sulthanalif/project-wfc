<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;

class LandingpageController extends Controller
{
    public function index()
    {
        return view('landing.welcome');
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
        $packages = Package::all();

        return view('landing.pages.catalogs', compact('packages'));
    }
}
