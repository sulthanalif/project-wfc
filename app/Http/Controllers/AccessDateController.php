<?php

namespace App\Http\Controllers;

use App\Models\AccessDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessDateController extends Controller
{
    //route name : access-date
    public function index()
    {
        $access_date = AccessDate::first();
        return view('cms.admin.option.access-date', compact('access_date'));
    }

    //route name : access-date.update
    public function update(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $access_date = AccessDate::first() ?? new AccessDate();
                $access_date->date = $request->date;
                $access_date->save();
            });
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 404
            ];

            return view('cms.error', $data);
        }
    }
}
