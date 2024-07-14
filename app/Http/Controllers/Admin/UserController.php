<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\SubAgent;
use App\Exports\AgentExport;
use App\Imports\AgentImport;
use App\Models\AdminProfile;
use Illuminate\Http\Request;
// use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\NotificationApproveUser;
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
        $users = User::latest()->paginate(10);

        // Tampilkan data ke view.
        return view('cms.admin.users.index', compact('users'));
    }

    public function export()
    {
        return Excel::download(new AgentExport, 'Agent_export_' . now() . '.xlsx');
    }



    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
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
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:225', 'string'],
            'email' => ['required', 'max:225', 'unique:users,email'],
            'password' => ['required'],
            'role' => ['required', 'string'],
            'address' => 'string',
            'phone_number' => 'string',
            'rt' => 'string',
            'rw' => 'string',
            'village' => 'string',
            'district' => 'string',
            'regency' => 'string',
            'province' => 'string',
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

                ($request->role) ? $user->assignRole($request->role) : 'Guest';
                if ($request->role !== "agent") {
                    $user->email_verified_at = now();
                    $user->adminProfile()->create([
                        'name' => $request->name
                    ]);
                } else {
                    $photoName = null;
                    if ($request->hasFile('photo')) {
                        $photoName = 'photo_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
                        Storage::disk('public')->put('images/photos/' . $photoName, $request->file('photo')->getContent());
                    }

                    $user->agentProfile()->create([
                        'name' => $request->name,
                        'photo' => $photoName,
                        'address' => $request->address,
                        'phone_number' => $request->phone_number,
                        'rt' => $request->rt,
                        'rw' => $request->rw,
                        'village' => $request->village,
                        'district' => $request->district,
                        'regency' => $request->regency,
                        'province' => $request->province,
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
            'phone_number' => 'string',
            'address' => 'string',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'rt' => 'string',
            'rw' => 'string',
            'village' => 'string',
            'district' => 'string',
            'regency' => 'string',
            'province' => 'string',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $user, &$update) {
                if ($user->roles->first()->name == "agent") {
                    if ($request->hasFile('photo')) {
                        if ($user->agentProfile->photo && file_exists(storage_path('app/public/images/photos/' . $user->agentProfile->photo))) {
                            unlink(storage_path('app/public/images/photos/' . $user->agentProfile->photo));
                        }

                        $photoName = 'photo_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
                        Storage::disk('public')->put('images/photos/' . $photoName, $request->file('photo')->getContent());

                        $user->agentProfile()->update([
                            'name' => $request->name,
                            'photo' => $photoName,
                            'phone_number' => $request->phone_number,
                            'address' => $request->address,
                            'rt' => $request->rt,
                            'rw' => $request->rw,
                            'village' => $request->village,
                            'district' => $request->district,
                            'regency' => $request->regency,
                            'province' => $request->province,
                        ]);
                    } else {

                        $user->agentProfile()->update([
                            'name' => $request->name,
                            // 'photo' => $photoName,
                            'phone_number' => $request->phone_number,
                            'address' => $request->address,
                            'rt' => $request->rt,
                            'rw' => $request->rw,
                            'village' => $request->village,
                            'district' => $request->district,
                            'regency' => $request->regency,
                            'province' => $request->province,
                        ]);
                    }
                } else {
                    $user->adminProfile()->update([
                        'name' => $request->name
                    ]);
                }
            });
            return redirect()->route('user.show', $user)->with('success', 'Data Berhasil Diupdate');
        } catch (\Exception $th) {
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

        if ($user->active == 1) {
            Mail::to($user->email)->send(new NotificationApproveUser);
        }

        return redirect()->route('user.index')->with('success', 'Data Berhasil ' . ($user->active == 1 ? 'Diaktifkan' : 'Dinonaktifkan'));
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

        if ($delete) {
            return redirect()->route('user.index', ['page' => $request->page])->with('success', 'User deleted successfully.');
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

    public function changeRole(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|string'
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $user) {
                $roleUser = $user->roles->first();
                $roleName = $roleUser->name;

                //remove role
                $user->removeRole($roleName);

                //add role
                $user->assignRole($request->role);
            });

            return back()->with('success', 'Role Berhasil Diubah');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }
}
