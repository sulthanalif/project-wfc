<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('cms.admin.profile.index', compact('user'));
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        try {
            DB::transaction(function () use ($request, $user) {
                $user->update([
                    'email' => $request->email,
                ]);

                if ($user->adminProfile) {
                    $user->adminProfile->update([
                        'name' => $request->name,
                    ]);
                } else {
                    \App\Models\AdminProfile::create([
                        'user_id' => $user->id,
                        'name' => $request->name,
                    ]);
                }
            });

            return back()->with('success', 'Profil (nama dan email) berhasil diperbarui!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal memperbarui profil: ' . $th->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah!');
        }

        try {
            DB::transaction(function () use ($request, $user) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            });

            return back()->with('success', 'Password berhasil diperbarui!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal memperbarui password: ' . $th->getMessage());
        }
    }
}
