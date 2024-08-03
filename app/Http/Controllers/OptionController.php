<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index()
    {
        $periods = Period::paginate(5);
        return view('cms.admin.options.index', compact('periods'));
    }

    public function selectPeriod(Request $request)
    {
        $period = Period::find($request->period_id);
        session()->put('period', $period);
        dd(session()->get('period'));
        return redirect()->back();
    }
}
