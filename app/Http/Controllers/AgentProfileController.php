<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AgentProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
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
    public function update(Request $request, User $user)
    {
        $profile = $user->agentProfile;

        $validateData = $request->validate([
            'address' => 'nullable|string'
        ]);

        $profile->update($validateData);

        return redirect()->route('cms.profile.index', $user);
    }


}
