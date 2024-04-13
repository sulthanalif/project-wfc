<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingpageController extends Controller
{
    public function index ()
    {
        return view('landing.welcome');
    }

    public function profile ()
    {
        return view('landing.pages.profile');
    }

    public function catalogs ()
    {
        return view('landing.pages.catalogs');
    }
}
