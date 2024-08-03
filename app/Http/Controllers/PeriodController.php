<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PeriodController extends Controller
{
    public function addOrUpdatePeriod(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'start_date' => 'required|date',
           'end_date' => 'required|date',
           'description' => 'required|string',
           'access_date' => 'required|date',
        //    'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            $period = Period::find($request->id);
            DB::transaction(function () use ($request, $period) {
                $data = $period ?? new Period();
                $data->start_date = $request->start_date;
                $data->end_date = $request->end_date;
                $data->access_date = $request->access_date;
                $data->description = $request->description;
                $data->save();
            });
            return back()->with('success', $period ? 'Period has been updated' : 'Period has been added');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];

            return view('cms.error', compact('data'));
        }
    }

    public function activatePeriod(Request $request)
    {
        try {
            $periodActive = Period::where('is_active', 1)->first();
            $newPeriod = Period::find($request->id);
            if ($periodActive) {
                $periodActive->is_active = 0;
                $periodActive->save();
                $newPeriod->is_active = 1;
                $newPeriod->save();
            } else {
                $newPeriod->is_active = 1;
                $newPeriod->save();
            }
            return back()->with('success', 'Period has been activated');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];

            return view('cms.error', compact('data'));
        }
    }

    public function deletePeriod(Period $period)
    {
        try {
            $period->delete();
            return back()->with('success', 'Period has been deleted');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];

            return view('cms.error', compact('data'));
        }
    }
}
