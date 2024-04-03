<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AgentProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
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

        if (!$agent->agentProfile){

            $profile = [
                'name' => $agent->name,
                'email' => $agent->email,
            ];

            return view('cms.profile.index', compact('profile'));
        }

        $profile = [
            'name' => $agent->name,
            'email' => $agent->email,
            'address' => $agent->agentProfile->address
        ];

        return view('cms.profile.index', compact('profile'));




    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $user)
    {
        $agent = Auth::user();

        if (!$agent->agentProfile){

            $profile = [
                'name' => $agent->name,
                'email' => $agent->email,
            ];

            return view('cms.profile.edit', compact('profile'));
        }

        $profile = [
            'name' => $agent->name,
            'email' => $agent->email,
            'address' => $agent->agentProfile->address
        ];

        return view('cms.profile.edit', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'address' => 'required|string'
        ]);

        if($validasi->fails()){
            return back()->with('error', $validasi->errors());
        }

        $agent = Auth::user();

        $profile = AgentProfile::create([
            'user_id' => $agent->id,
            'address' => $request->input('address')
        ]);

        if(!$profile){
            return back()->with('error', 'Profile Gagal Update');
        }
        return back()->with('success', 'Profile Berhasil Terupdate');
    }


}
