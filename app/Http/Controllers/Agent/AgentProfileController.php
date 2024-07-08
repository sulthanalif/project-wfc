<?php

namespace App\Http\Controllers\Agent;

use App\Models\User;
use App\Models\AgentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

// use App\Http\Requests\StoreagentProfileRequest;
// use App\Http\Requests\UpdateagentProfileRequest;

class AgentProfileController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $agent = Auth::user();

        return view('cms.profile.index', compact('agent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $user)
    {
        $agent = Auth::user();

        return view('cms.profile.edit', compact('agent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateProfile(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'name' => 'required|string',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'string',
            'phone_number' => 'string',
            'rt' => 'string',
            'rw' => 'string',
            'village' => 'string',
            'district' => 'string',
            'regency' => 'string',
            'province' => 'string',
        ]);

        if ($validasi->fails()) {
            return back()->with('error', $validasi->errors());
        }

        $agent = Auth::user();

        try {
            DB::transaction(function () use ($request, $agent, &$update) {
                if ($request->hasFile('photo')) {
                    // Delete old photo
                    if ($agent->agentProfile->photo && file_exists(storage_path('app/public/images/photos/' . $agent->id . '/' . $agent->agentProfile->photo))) {
                        unlink(storage_path('app/public/images/photos/' . $agent->id . '/' . $agent->agentProfile->photo));
                    }

                    $photoName = 'photo_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
                    Storage::disk('public')->put('images/photos/' . $agent->id . '/' . $photoName, $request->file('photo')->getContent());

                    // $photoName = 'photo_'.time() . '.' . $request->file('photo')->getClientOriginalExtension();
                    // $request->file('photo')->storeAs('public/photos/'.$agent->id. '/', $photoName);

                    $update = $agent->agentProfile->update([
                        'name' => $request->name,
                        'photo' => $photoName,
                        'phone_number' => $request->phone_number,
                        'address' => $request->address,
                        'rt' => $request->rt,
                        'rw' => $request->rw,
                        'village' => $request->village,
                        'district' => $request->district,
                        'regency' => $request->regency,
                        'province' => $request->province,
                    ]);
                } else {
                    $update = $agent->agentProfile->update($request->except('photo'));
                }
            });
            if (!$update) {
                return back()->with('error', 'Data Tidak Berhasil Diubah!');
            } else {
                return redirect()->route('users.profile', $agent)->with('success', 'Data Berhasil Diubah');
            }
        } catch (\Exception $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }
}
