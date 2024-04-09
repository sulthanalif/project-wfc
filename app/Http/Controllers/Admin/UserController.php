<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $n = 1;
       // Tampilkan data ke view.
       return view('cms.admin.users.index', compact('users' ,'n'));
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
            'name' => ['nullable', 'max:225', 'string'],
            'email' => ['required', 'max:25', 'unique:users,email'],
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
            return response()->json([
                'message' => $th->getMessage(),
            ], 400);
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
        return view('users.edit', compact('user'));
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
            'name' => ['nullable', 'max:225', 'string'],
            'email' => ['required', 'max:25', 'unique:users,email'],
            'password' => ['required'],
            'role' => ['required', 'string'],
            'address' => ['string'],
            'phone_number' => ['string'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $user, &$update) {
                $user->update([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                ($request->role) ? $user->assignRole($request->role) : '';

                if($request->role !== "agent"){
                    $user->email_verified_at = now();
                } else {
                    $user->agentProfile()->create([
                        'name' => $request->name,
                        'address' => $request->address,
                        'phone_number' => $request->phone_number
                    ]);
                }

            });
            if (!$update) {
                return back()->with('error', 'Data Tidak Berhasil Diubah!');
            } else {
                return redirect()->route('user.index')->with('success', 'Data Berhasil Diubah');
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 400);
        }


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
}
