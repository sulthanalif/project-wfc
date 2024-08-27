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

        if ($roleName == 'agent') {
            $perPages = $request->get('perPage') ?? 5;
    
            if ($perPages == 'all') {
                $subAgents = SubAgent::where('agent_id', $user->id)->get();
            } else {
                $perPage = intval($perPages);
                $subAgents = SubAgent::where('agent_id', $user->id)->paginate($perPage);
            }
            return view('cms.sub-agent.index', compact('subAgents'));
        } else {
            $perPages = $request->get('perPage') ?? 5;
    
            if ($perPages == 'all') {
                $subAgents = SubAgent::all();
            } else {
                $perPage = intval($perPages);
                $subAgents = SubAgent::paginate($perPage);
            }
            return view('cms.sub-agent.index', compact('subAgents'));
        }

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
        $delete = $subAgent->delete();

        if ($delete) {
            return redirect()->route('sub-agent.index', ['page' => $request->page])->with('success', 'Data Berhasil Dihapus!');
        } else {
            return back()->with('error', 'Data Gagal Dihapus!');
        }

    }
}
