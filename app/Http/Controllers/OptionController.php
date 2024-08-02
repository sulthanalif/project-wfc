<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index()
    {
        $periods = Period::paginate(5);
        return view('cms.options.index', compact('periods'));
    }
}
