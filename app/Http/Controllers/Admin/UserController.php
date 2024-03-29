<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
       // Pastikan hanya super admin yang dapat mengakses halaman ini.
    //    $this->authorize('viewAny', User::class);

       // Query untuk mengambil data user.
       $users = User::query();

       // Terapkan filter berdasarkan request (q)
       if ($request->has('q')) {
        $users->where('name', 'like', '%'.$request->get('q').'%');
    }

       // Paginasi data dengan 10 data per halaman.
       $users = $users->paginate(10);

       // Tampilkan data ke view.
       return view('users.index', compact('users'));
    }

    /**
     * Tampilkan formulir untuk membuat pengguna baru.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Pastikan hanya super admin yang dapat mengakses halaman ini.
        // $this->authorize('create', User::class);

        return view('users.create');
    }

    /**
     * Simpan pengguna baru.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Pastikan hanya super admin yang dapat mengakses halaman ini.
        // $this->authorize('create', User::class);

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create($validatedData);
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
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
        // // Pastikan hanya super admin yang dapat mengakses halaman ini.
        // $this->authorize('view', $user);

        return view('users.show', compact('user'));
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
        // Pastikan hanya super admin yang dapat mengakses halaman ini.
        // $this->authorize('update', $user);

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
        // Pastikan hanya super admin yang dapat mengakses halaman ini.
        // $this->authorize('update', $user);

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
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
        // Pastikan hanya super admin yang dapat mengakses halaman ini.
        // $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
