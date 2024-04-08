<?php

namespace App\Http\Controllers\Agent;

use App\Models\User;
use App\Models\AgentProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

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
        $validasi = Validator::make(Request::all(), [
            'name' => 'required|string',
            'address' => 'required|string'
        ]);

        if($validasi->fails()){
            return back()->with('error', $validasi->errors());
        }

        $agent = Auth::user();

        $update = $agent->agentProfile->update(Request::all());

        if(!$update) {
            return back()->with('error', 'Data Tidak Berhasil Diubah!');
        } else {
            return view('cms.profile.index', compact('agent'))->with('success', 'Data Berhasil Diubah');
        }
    }


}
