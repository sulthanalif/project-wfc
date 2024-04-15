<?php

namespace App\Http\Controllers;

use App\Models\Administration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdministrationController extends Controller
{
    public function index()
    {
        return view('cms.agen.new-agent.index');
    }

    public function waiting()
    {
        return view('cms.agen.new-agen.waiting');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ktp' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'kk' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'sPerjanjian' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, &$upload) {
                $imageNameKTP = 'administration_'.time(). '_ktp' . '.' . $request->file('ktp')->getClientOriginalExtension();
                $imageNameKK = 'administration_'.time(). '_kk' . '.' . $request->file('kk')->getClientOriginalExtension();
                $imageNameSPerjanjian = 'administration_'.time(). '_sPerjanjian' . '.' . $request->file('sPerjanjian')->getClientOriginalExtension();

                $upload = Administration::create([
                    'user_id' => Auth::user()->id,
                    'ktp' => $imageNameKTP,
                    'kk' => $imageNameKK,
                    'sPerjanjian' => $imageNameSPerjanjian
                ]);

                $request->file('ktp')->storeAs('public/images/administration/'. Auth::user()->id .'/', $imageNameKTP);
                $request->file('kk')->storeAs('public/images/administration/'. Auth::user()->id .'/', $imageNameKK);
                $request->file('sPerjanjian')->storeAs('public/images/administration/'. Auth::user()->id .'/', $imageNameSPerjanjian);
            });
            if ($upload) {
                return redirect()->route('waiting')->with('success', 'Data Sukses Diunggah!');
            } else {
                return back()->with('error', 'Data Gagal Diunggah!');
            }
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function acc()
    {

    }
}
