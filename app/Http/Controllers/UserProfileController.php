<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreUserProfileRequest;
use App\Http\Requests\UpdateUserProfileRequest;

class UserProfileController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        $profile = $user->userProfile;

        view('user.profile', compact('user', 'profile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $user)
    {
        $profile = $user->userProfile;

        view('user.profile', compact('user', 'profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $profile = $user->userProfile;

        $validateData = $request->validate([
            'address' => 'nullable|string'
        ]);

        $profile->update($validateData);

        return redirect()->route('user.profile', $user);
    }


}
