<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function registerPost (Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password1' => 'required|password',
            'password2' => 'required|password'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }



        if ($request->password1 != $request->password2) {
            return ResponseFormatter::error('', 'Password dan Konfirmasi Password Tidak Sama');
        }

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password1);

        $user->save();


        if ($user) {
            return back()->with('success', 'Registrasi Berhasil!');
        } else {
            return back()->with('error', 'Registrasi Tidak Berhasil!');
        }

    }

    public function login ()
    {
        return view('login');
    }

    public function loginPost (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error('', $validator->errors());
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)){
            return redirect('/admin')->with('success', 'Login Berhasil!');
        } else {
            return back()->with('error', 'Email atau Password Salah!');
        }


    }
}
