<?php

namespace App\Http\Controllers\Admin\Landingpage;

use App\Http\Controllers\Controller;
use App\Models\Benefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BenefitController extends Controller
{
    public function index()
    {
        $benefit = Benefit::first();
        return view('cms.admin.landingpage.benefit', [
            'benefit' => $benefit
        ]);
    }

    public function update(Request $request, Benefit $benefit)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'subtitle' => 'required',
            'benefit_agen' => 'required',
            'benefit_mitra' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $benefit) {
                $benefit->update([
                    'title' => $request->title,
                    'subtitle' => $request->subtitle,
                    'benefit_agen' => $request->benefit_agen,
                    'benefit_mitra' => $request->benefit_mitra
                ]);
            });
            return redirect()->back()->with('success', 'Benefit updated successfully');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400,
            ];
            return view('cms.error', ['data' => $data]);
        }
    }
}
