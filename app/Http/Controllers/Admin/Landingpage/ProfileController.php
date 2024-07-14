<?php

namespace App\Http\Controllers\Admin\Landingpage;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = Profile::first();
        return view('admin.landingpage.profile', [
            'profile' => $profile
        ]);
    }

    public function update(Request $request, Profile $profile)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
            'buttonTitle' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $profile) {
                //delete old image
                if ($profile->image && file_exists(storage_path('app/public/images/landingpage/' . $profile->image))) {
                    unlink(storage_path('app/public/images/landingpage/' . $profile->image));
                }

                //save image
                $imageName = 'profile_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                Storage::disk('public')->put('images/landingpage/' . $imageName, $request->file('image')->getContent());

                $profile->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'image' => $imageName,
                    'buttonTitle' => $request->buttonTitle
                ]);
            });
            return redirect()->back()->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400,
            ];
            return view('cms.error', ['data' => $data]);
        }
    }
}
