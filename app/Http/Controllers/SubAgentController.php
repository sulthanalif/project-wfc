<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SubAgent;
use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
use App\Exports\SubAgentExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreSubAgentRequest;
use App\Http\Requests\UpdateSubAgentRequest;

class SubAgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $roleUser = $user->roles->first();
        $roleName = $roleUser->name;

        $perPages = $request->get('perPage') ?? 10;
        $query = SubAgent::query();

        if ($roleName == 'agent') {
            $query->where('agent_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($perPages == 'all') {
            $subAgents = $query->take(100)->get();
        } else {
            $perPage = min(max(intval($perPages), 1), 100);
            $subAgents = $query->paginate($perPage)->withQueryString();
        }
        return view('cms.sub-agent.index', compact('subAgents'));

    }

    public function export()
    {
        return Excel::download(new SubAgentExport, 'SubAgent_export_'.now().'.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $roleUser = $user->roles->first();
        $roleName = $roleUser->name;

        if ($roleName == 'agent')  {
            return view('cms.sub-agent.create');
        } else {
            //untuk pilihan sub agent dari mana
            $agents = User::role('agent')->where('active', 1)->get();

            return view('cms.sub-agent.create', compact('agents'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'super_admin'])) {
            $request->merge(['agent_id' => $user->id]);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'phone_number' => ['required', 'string', 'max:15'],
            'agent_id' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            // $error = $validator->errors();
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, &$store) {
                    $store = SubAgent::create([
                        'agent_id' => $request->agent_id,
                        'name' => $request->name,
                        'address' => $request->address,
                        'phone_number' => $request->phone_number
                    ]);
            });
            if ($store) {
                return redirect()->route('sub-agent.index')->with('success', 'Data Berhasil Ditambahkan');
            } else {
                return back()->with('error', 'Data Gagal Ditambahkan!');
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
     * Display the specified resource.
     */
    public function show(SubAgent $subAgent)
    {
        if (auth()->user()->hasRole('agent') && $subAgent->agent_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($subAgent) {
            return view('cms.sub-agent.detail', compact('subAgent'));
        } else {
            return back()->with('error', 'Data Tidak Ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubAgent $subAgent)
    {
        if (auth()->user()->hasRole('agent') && $subAgent->agent_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();
        $roleUser = $user->roles->first();
        $roleName = $roleUser->name;

        if ($roleName == 'agent')  {
            return view('cms.sub-agent.edit', compact('subAgent'));
        } else {
            //untuk pilihan sub agent dari mana
            $agents = User::role('agent')->get();

            return view('cms.sub-agent.edit', compact('agents', 'subAgent'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubAgent $subAgent)
    {
        $user = Auth::user();
        if ($user->hasRole('agent')) {
            if ($subAgent->agent_id !== $user->id) {
                abort(403, 'Unauthorized');
            }
            $request->merge(['agent_id' => $user->id]);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'phone_number' => ['required', 'string', 'max:15'],
            'agent_id' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            // $error = $validator->errors();
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $subAgent, &$update) {
                    $update = $subAgent->update([
                        'agent_id' => $request->agent_id,
                        'name' => $request->name,
                        'address' => $request->address,
                        'phone_number' => $request->phone_number
                    ]);
            });
            if ($update) {
                return redirect()->route('sub-agent.index')->with('success', 'Data Berhasil Diubah');
            } else {
                return back()->with('error', 'Data Gagal Diubah!');
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
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, SubAgent $subAgent)
    {
        if (auth()->user()->hasRole('agent') && $subAgent->agent_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $delete = false;

        if ($subAgent->orderDetail()->count() == 0) {
            $delete = $subAgent->delete();
        }

        if ($delete) {
            return redirect()->route('sub-agent.index', ['page' => $request->page])->with('success', 'Data Berhasil Dihapus!');
        } else {
            return back()->with('error', 'Data Gagal Dihapus!');
        }

    }
}
