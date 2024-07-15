<?php

namespace App\Http\Controllers\Admin\Landingpage;

use Illuminate\Http\Request;
use App\Models\DetailProfile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DetailProfileController extends Controller
{
    public function index()
    {
        $detailProfile = DetailProfile::first();
        return view('cms.admin.landingpage.detail-profile', [
            'detailProfile' => $detailProfile
        ]);
    }

    public function update(Request $request, DetailProfile $detailProfile)
    {
        $validator = Validator::make($request->all(), [
            'titleHistory' => 'required',
            'bodyHistory' => 'required',
            'image' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'titleVM' => 'required',
            'vision' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($detailProfile, $request) {
                if ($request->hasFile('image')) {
                    // Delete old image
                    if ($detailProfile->image && file_exists(storage_path('app/public/images/landingpage/' . $detailProfile->image))) {
                        unlink(storage_path('app/public/images/landingpage/' . $detailProfile->image));
                    }

                    //sava image
                    $imageName = 'detailProfile_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                    Storage::disk('public')->put('images/landingpage/' . $imageName, $request->file('image')->getContent());

                    $detailProfile->update([
                        'titleHistory' => $request->titleHistory,
                        'bodyHistory' => $request->bodyHistory,
                        'image' => $imageName,
                        'titleVM' => $request->titleVM,
                        'vision' => $request->vision,
                    ]);
                } else {
                    $detailProfile->update([
                        'titleHistory' => $request->titleHistory,
                        'bodyHistory' => $request->bodyHistory,
                        'titleVM' => $request->titleVM,
                        'vision' => $request->vision,
                    ]);
                }
            });
            return redirect()->back()->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => $e->getCode()
            ];

            return view('cms.error', compact('data'));
        }
    }

    public function addMission(Request $request, DetailProfile $detailProfile)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($detailProfile, $request) {
                Mission::create([
                    'detail_profile_id' => $detailProfile->id,
                    'content' => $request->content
                ]);
            });
            return redirect()->back()->with('success', 'Mission added successfully');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => $e->getCode()
            ];

            return view('cms.error', compact('data'));
        }
    }

    public function deleteMission(Mission $mission)
    {
        try {
            DB::transaction(function () use ($mission) {
                $mission->delete();
            });
            return redirect()->back()->with('success', 'Mission deleted successfully');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => $e->getCode()
            ];

            return view('cms.error', compact('data'));
        }
    }
}
