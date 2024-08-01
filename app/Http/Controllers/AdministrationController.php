<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Administration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdministrationController extends Controller
{
    public function index()
    {

        if (Auth::user()->active == 0){
            return view('cms.agen.new-agent.index');
        } else {
            return redirect()->route('dashboard-agent');
        }
    }

    public function waiting()
    {
        if (Auth::user()->active == 0) {
            return view('cms.agen.new-agent.waiting');
        } else {
            return redirect()->route('dashboard-agent');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ktp' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            // 'kk' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'sPerjanjian' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, &$upload) {
                $imageNameKTP = 'administration_'.Auth::user()->id. '_ktp' . '.' . $request->file('ktp')->getClientOriginalExtension();
                // $imageNameKK = 'administration_'.Auth::user()->id. '_kk' . '.' . $request->file('kk')->getClientOriginalExtension();
                $imageNameSPerjanjian = 'administration_'.Auth::user()->id. '_sPerjanjian' . '.' . $request->file('sPerjanjian')->getClientOriginalExtension();

                Storage::disk('public')->put('images/administration/'. $imageNameKTP, $request->file('ktp')->getContent());
                // Storage::disk('public')->put('images/administration/'. $imageNameKK, $request->file('kk')->getContent());
                Storage::disk('public')->put('images/administration/'. $imageNameSPerjanjian, $request->file('sPerjanjian')->getContent());

                $upload = Administration::create([
                    'user_id' => Auth::user()->id,
                    'ktp' => $imageNameKTP,
                    // 'kk' => $imageNameKK,
                    'sPerjanjian' => $imageNameSPerjanjian
                ]);

                // $request->file('ktp')->storeAs('public/images/administration/'. Auth::user()->id .'/', $imageNameKTP);
                // $request->file('kk')->storeAs('public/images/administration/'. Auth::user()->id .'/', $imageNameKK);
                // $request->file('sPerjanjian')->storeAs('public/images/administration/'. Auth::user()->id .'/', $imageNameSPerjanjian);

            });
            if ($upload) {
                return redirect()->route('waiting')->with('success', 'Data Sukses Diunggah!');
            } else {
                return back()->with('error', 'Data Gagal Diunggah!');
            }
        } catch (\Exception $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function getAdministration(Request $request, User $user)
    {
        if ($user) {
            return view('cms.admin.administrations.index', compact('user'));
        } else {
            return back()->with('error', 'Data Tidak Ditemukan');
        }
    }
}
