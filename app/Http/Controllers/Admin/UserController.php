<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'email' => ['required', 'max:225', 'unique:users,email'],
            'password' => ['required'],
            'role' => ['required', 'string'],
            'address' => ['nullable', 'string'],
            'phone_number' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, &$user) {
                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                ($request->role) ? $user->assignRole($request->role) : 'Guest' ;
                if($request->role !== "agent"){
                    $user->email_verified_at = now();
                    $user->adminProfile()->create([
                        'name' => $request->name
                    ]);
                } else {
                    $user->agentProfile()->create([
                        'name' => $request->name,
                        'address' => $request->address,
                        'phone_number' => $request->phone_number
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

        return view('cms.admin.users.detail', compact('user'));
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
            'email' => ['required', 'max:25', 'unique:users,email'],
            'password' => ['nullable', 'string'],
            'role' => ['required', 'string'],
            'address' => ['string'],
            'phone_number' => ['string'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $user, &$update) {
                if ($request->password) {
                    $user->update([
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                    ]);
                } else {
                    $user->update([
                        'email' => $request->email
                    ]);
                }

                ($request->role) ? $user->assignRole($request->role) : '';

                if($request->role == "agent"){
                    $user->agentProfile()->update([
                        'name' => $request->name,
                        'address' => $request->address,
                        'phone_number' => $request->phone_number
                    ]);
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

        return back()->with('success', 'Data Berhasil '. ($user->active == 1 ? 'Diaktifkan' : 'Dinonaktifkan') );
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


        $delete = $user->delete();

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
