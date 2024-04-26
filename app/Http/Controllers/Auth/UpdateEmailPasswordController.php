<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UpdateEmailPasswordController extends Controller
{
    public function changeEmail()
    {
        return view('cms.agen.email');
    }

    public function checkEmail(Request $request)
    {
        $checking = User::where('email', $request->email)->get();

        if ($checking) {
            return back()->with('error', 'Email Tidak Dapat Digunakan!');
        } else {
            return back()->with('success', 'Email Dapat Digunakan');
        }
    }

    public function updateEmail(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users,email']
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $user, &$update) {
                $update = $user->update([
                    'email' => $request->email
                ]);
            });
            if ($update) {
                return redirect()->route('users.profile')->with('success', 'Email Berhasil Diperbaharui!');
            } else {
                return back()->with('error', 'Email Gagal Diperbaharui');
            }
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function changePassword()
    {
        return view('cms.agen.password');
    }

    public function updatePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string'],
            'newPass' => ['required', 'string'],
            'confirmPass' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $user, &$update) {
                $passwordUser = Auth::user()->password;
                $password = Hash::make($request->password);
                $newPass = Hash::make($request->newPass);
                $confirmPass = Hash::make($request->confirmPass);

                if ($passwordUser == $password){
                    if ($password == $newPass) {
                        return back()->with('error', 'Pasword baru harus berbeda dengan yang lama!');
                    } else {
                        if ($newPass == $confirmPass) {

                            $update = $user->update([
                                'password' => $newPass
                            ]);

                        } else {
                            return back()->with('error', 'Password baru harus sama dengan konfirmasi password!');
                        }
                    }
                } else {
                    return back()->with('error', 'Password anda salah!');
                }
            });
            if ($update) {
                return redirect()->route('users.profile')->with('success', 'Password berhasil diubah');
            } else {
                return back()->with('error', 'Password Gagal Diubah!');
            }
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }
}
