<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\ValidateRole;
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
        if (Auth::user()->active == 0) {
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
            'ktp' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            // 'kk' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'sPerjanjian' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ], [
            'required' => ':attribute wajib dipilih.',
            'uploaded' => ':attribute gagal terkirim, kemungkinan ukuran file terlalu besar.',
            'image' => ':attribute harus berupa foto JPG, PNG, atau GIF.',
            'mimes' => ':attribute harus berupa foto JPG, PNG, atau GIF.',
            'max' => 'Ukuran :attribute maksimal 10 MB.',
        ], [
            'ktp' => 'Foto KTP',
            'sPerjanjian' => 'Foto Surat Perjanjian',
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Berkas belum terkirim. ' . implode(' ', $validator->errors()->all()));
        }

        try {
            DB::transaction(function () use ($request, &$upload) {
                $imageNameKTP = 'administration_' . Auth::user()->id . '_ktp' . '.' . $request->file('ktp')->getClientOriginalExtension();
                // $imageNameKK = 'administration_'.Auth::user()->id. '_kk' . '.' . $request->file('kk')->getClientOriginalExtension();
                $imageNameSPerjanjian = 'administration_' . Auth::user()->id . '_sPerjanjian' . '.' . $request->file('sPerjanjian')->getClientOriginalExtension();

                $this->saveFile($imageNameKTP, $request->file('ktp')->getContent());
                // Storage::disk('public')->put('images/administration/'. $imageNameKK, $request->file('kk')->getContent());
                $this->saveFile($imageNameSPerjanjian, $request->file('sPerjanjian')->getContent());

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

    public function update(Request $request, User $user)
    {
        if (ValidateRole::check('agent') && $user->id !== Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'ktp' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'sPerjanjian' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        $administration = $user->administration;
        $hasKtp = $request->hasFile('ktp');
        $hasPerjanjian = $request->hasFile('sPerjanjian');

        if (!$hasKtp && !$hasPerjanjian) {
            return back()->with('error', 'Pilih minimal satu file untuk diunggah.');
        }
        // Kolom ktp dan sPerjanjian wajib terisi, jadi baris baru butuh kedua file.
        if (!$administration && !($hasKtp && $hasPerjanjian)) {
            return back()->with('error', 'Agen ini belum punya berkas. Unggah Foto KTP dan Surat Perjanjian sekaligus.');
        }

        try {
            DB::transaction(function () use ($request, $user, $administration) {
                $data = [];
                foreach (['ktp', 'sPerjanjian'] as $field) {
                    if (!$request->hasFile($field)) {
                        continue;
                    }
                    $name = 'administration_' . $user->id . '_' . $field . '.' . $request->file($field)->getClientOriginalExtension();
                    $this->saveFile($name, $request->file($field)->getContent());
                    if ($administration?->$field && $administration->$field !== $name) {
                        Storage::disk('public')->delete('images/administration/' . $administration->$field);
                    }
                    $data[$field] = $name;
                }

                $user->administration()->updateOrCreate([], $data);
            });

            return redirect()->back()->with('success', 'Data Sukses Diunggah!');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage() . $e->getLine(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    // Disk public memakai throw=false, jadi put() yang gagal hanya mengembalikan false.
    private function saveFile(string $name, string $content): void
    {
        if (!Storage::disk('public')->put('images/administration/' . $name, $content)) {
            throw new \Exception('Gagal menyimpan file ' . $name . ' ke server.');
        }
    }
}
