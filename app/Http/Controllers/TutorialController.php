<?php

namespace App\Http\Controllers;

use App\Models\Tutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\TutorialController;

class TutorialController extends Controller
{
    public function index(Request $request)
    {
        $perPages = $request->get('perPage') ?? 5;

        if ($perPages == 'all') {
            $datas = Tutorial::latest()->get();
        } else {
            $perPage = intval($perPages);
            $datas = Tutorial::latest()->paginate($perPage);
        }

        $roles = Role::where('name', '!=', 'super_admin')->get();

        return view('cms.admin.tutorials.index', compact('datas', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'required|string|max:255',
            'role' => 'required|string',
            'status' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            $tutorial = Tutorial::create([
                'title' => $request->title,
                'description' => $request->description,
                'link' => $request->link,
                'role' => $request->role,
                'status' => $request->status,
            ]);
            
            DB::commit();
            return redirect()->route('tutorial.index')->with('success', 'Tutorial berhasil ditambahkan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create tutorial: ' . $e->getMessage())
                ->withInput();
        }

    }

    public function update(Request $request, Tutorial $tutorial)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'required|string|max:255',
            'role' => 'required|string',
            'status' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            $tutorial->update([
                'title' => $request->title,
                'description' => $request->description,
                'link' => $request->link,
                'role' => $request->role,
                'status' => $request->status,
            ]);
            
            DB::commit();
            return redirect()->route('tutorial.index')->with('success', 'Tutorial berhasil diupdate');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update tutorial: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Tutorial $tutorial)
    {
        try {
            DB::beginTransaction();

            $tutorial->delete();
            
            DB::commit();
            return redirect()->route('tutorial.index')->with('success', 'Tutorial berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete tutorial: ' . $e->getMessage());
        }
    }


    public function tutorialPage(Request $request)
    {
        $tutorials = Tutorial::where('role', Auth::user()->roleName)->get();

        return view('cms.tutorial', compact('tutorials'));
    }

}
