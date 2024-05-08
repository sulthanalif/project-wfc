<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\SubAgent;
use App\Exports\AgentExport;
use App\Imports\AgentImport;
use App\Models\AdminProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Tampilkan daftar pengguna.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $users = User::paginate(10);

       // Tampilkan data ke view.
       return view('cms.admin.users.index', compact('users' ));
    }

    public function export()
    {
        return Excel::download(new AgentExport, 'Agent_export_'.now().'.xlsx');
    }



    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if($validator->fails()){
            return back()->with('error', $validator->errors());
        }

        try {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            // Storage::disk('public')->put('doc/package/' . $fileName, $request->file('file')->getContent());

            Excel::import(new AgentImport, $file);

            return redirect()->route('user.index')->with('success', 'Data Berhasil Diimport');


        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    /**
     * Tampilkan formulir untuk membuat pengguna baru.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {

        return view('cms.admin.users.tambah');
    }

    /**
     * Simpan pengguna baru.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:225', 'string'],
            // 'email' => ['required', 'max:225', 'unique:users,email'],
            'password' => ['required'],
            'role' => ['required', 'string'],
            // 'address' => 'required|string',
            'phone_number' => 'string',
            'rt' => 'string',
            'rw'=> 'string',
            'village'=> 'string',
            'district'=> 'string',
            'regency'=> 'string',
            'province'=> 'string',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, &$user) {
                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'active' => 0,
                ]);

                ($request->role) ? $user->assignRole($request->role) : 'Guest' ;
                if($request->role !== "agent"){
                    $user->email_verified_at = now();
                    $user->adminProfile()->create([
                        'name' => $request->name
                    ]);
                } else {
                    $photoName = 'photo_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
                    Storage::disk('public')->put('photos/' .$user->id. '/' . $photoName, $request->file('photo')->getContent());

                    $user->agentProfile()->create([
                        'name' => $request->name,
                        'photo' => $photoName,
                        // 'address' => $request,
                        'phone_number' => $request->phone_number,
                        'rt' => $request->rt,
                        'rw'=> $request->rw,
                        'village'=> $request->village,
                        'district'=> $request->district,
                        'regency'=> $request->regency,
                        'province'=> $request->province,
                    ]);
                }
            });
            if (!$user) {
                return back()->with('error', 'Data Tidak Berhasil Ditambah!');
            } else {
                return redirect()->route('user.index')->with('success', 'Data Berhasil Ditambah');
            }
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }


    }

    /**
     * Tampilkan detail pengguna.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function show(Request $request, User $user)
    {
        $subAgents = SubAgent::where('agent_id', $user->id)->get();
        // dd($subAgents);

        return view('cms.admin.users.detail', compact(['user', 'subAgents']));
    }

    /**
     * Tampilkan formulir untuk mengedit pengguna.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, User $user)
    {
        return view('cms.admin.users.edit', compact('user'));
    }

    /**
     * Perbarui data pengguna.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:225', 'string'],
            // 'email' => ['required', 'max:25', 'unique:users,email'],
            'password' => ['nullable', 'string'],
            'role' => ['required', 'string'],
            'phone_number' => 'string',
            'rt' => 'string',
            'rw'=> 'string',
            'village'=> 'string',
            'district'=> 'string',
            'regency'=> 'string',
            'province'=> 'string',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $user, &$update) {
                // if ($request->password) {
                //     $user->update([
                //         'email' => $request->email,
                //         'password' => Hash::make($request->password),
                //     ]);
                // } else {
                //     $user->update([
                //         'email' => $request->email
                //     ]);
                // }


                if ($request->filled('password')) {
                    $user->update([
                        // 'email' => $request->email,
                        'password' => Hash::make($request->password),
                    ]);
                }

                ($request->role) ? $user->assignRole($request->role) : '';

                if($request->role == "agent"){
                    if ($request->hasFile('photo')) {
                        if ($user->agentProfile->photo && file_exists(storage_path('app/public/photos/'.$user->id. '/'. $user->agentProfile->photo))) {
                            unlink(storage_path('app/public/photos/' . $user->agentProfile->photo));
                        }

                        $photoName = 'photo_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
                        Storage::disk('public')->put('photos/' .$user->id. '/' . $photoName, $request->file('photo')->getContent());

                        $user->agentProfile()->update([
                            'name' => $request->name,
                            'photo' => $photoName,
                            'phone_number' => $request->phone_number,
                            'rt' => $request->rt,
                            'rw'=> $request->rw,
                            'village'=> $request->village,
                            'district'=> $request->district,
                            'regency'=> $request->regency,
                            'province'=> $request->province,
                        ]);
                    } else {

                    }

                } else {
                    $user->adminProfile()->update([
                        'name' => $request->name
                    ]);
                }

            });
            if (!$update) {
                return back()->with('error', 'Data Tidak Berhasil Diubah!');
            } else {
                return redirect()->route('user.index')->with('success', 'Data Berhasil Diubah');
            }
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }


    }

    public function changeStatus(Request $request, User $user)
    {

        $user->active = ($user->active == 1 ? 0 : 1);
        $user->save();

        return redirect()->route('user.index')->with('success', 'Data Berhasil '. ($user->active == 1 ? 'Diaktifkan' : 'Dinonaktifkan') );
    }

    /**
     * Hapus pengguna.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, User $user)
    {
        $roleUser = $user->roles->first();
        $roleName = $roleUser->name;

        if ($roleName == 'agent') {
            $user->agentProfile()->delete();
            $delete = $user->delete();
        } else {
            $user->adminProfile()->delete();
            $delete = $user->delete();
        }

        if ($delete){
            return redirect()->route('user.index' ,['page' => $request->page])->with('success', 'User deleted successfully.');
        } else {
            return back()->with('error', 'Data Tidak Bisa Dihapus!');
        }
    }

    public function getAdmin(Request $request)
    {
        $admins = User::role(['admin', 'super_admin', 'finance_admin'])->paginate(10);

        if ($admins) {
            return view('cms.admin.users.admin', compact('admins'));
        } else {
            return back()->with('error', 'Data Tidak Ditemukan!');
        }
    }

    public function getAgent(Request $request)
    {
        $agents = User::role(['agent'])->paginate(10);

        if ($agents) {
            return view('cms.admin.users.agent', compact('agents'));
        } else {
            return back()->with('error', 'Data Tidak Ditemukan!');
        }
    }
}
