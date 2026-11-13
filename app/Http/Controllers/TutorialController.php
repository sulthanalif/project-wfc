<?php

namespace App\Http\Controllers;

use App\Models\Tutorial;
use Illuminate\Http\Request;
use App\Helpers\TableFilter;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\TutorialController;

class TutorialController extends Controller
{
    public function index(Request $request)
    {
        $query = Tutorial::latest();

        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $filters = TableFilter::apply($query, $request, [
            'role' => ['label' => 'Role', 'type' => 'select', 'options' => ['admin' => 'Admin', 'finance_admin' => 'Admin Keuangan', 'agent' => 'Agen']],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['1' => 'Aktif', '0' => 'Nonaktif']],
        ]);

        $perPage = min(max((int) ($request->get('perPage') === 'all' ? 50 : $request->get('perPage', 10)), 1), 50);
        $datas = $query->paginate($perPage)->withQueryString();

        $roles = Role::where('name', '!=', 'super_admin')->get();

        return view('cms.admin.tutorials.index', compact('datas', 'roles', 'filters'));
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
