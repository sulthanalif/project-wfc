<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RewardController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $perPages = $request->get('perPage') ?? 5;

        if ($perPages == 'all') {
            $rewards = Reward::latest()->get();
        } else {
            $perPage = intval($perPages);
            $rewards = Reward::latest()->paginate($perPage);
        }


        return view('cms.admin.rewards.index', compact('rewards'));
    }

    public function show(Reward $reward)
    {
        $period = $reward->period;

        // Collect winners from agents (agent-level details only)
        $winners = [];
        $agents = \App\Models\User::role('agent')->where('active', 1)->get();
        foreach ($agents as $agent) {
            $orders = \App\Models\Order::where('agent_id', $agent->id)
                ->where('status', 'accepted')
                ->when($period, function ($q) use ($period) {
                    if ($period->start_date && $period->end_date) {
                        $q->whereBetween('order_date', [$period->start_date, $period->end_date]);
                    }
                })
                ->whereHas('detail', function ($q) {
                    $q->whereNull('sub_agent_id');
                })
                ->get();

            $totalProduct = 0;
            foreach ($orders as $order) {
                foreach ($order->detail->whereNull('sub_agent_id') as $detail) {
                    $totalProduct += (int) $detail->qty;
                }
            }

            if ($totalProduct >= $reward->target_qty) {
                $winners[] = [
                    'type' => 'agent',
                    'id' => $agent->id,
                    'name' => optional($agent->agentProfile)->name ?? $agent->email,
                    'total_product' => $totalProduct,
                ];
            }
        }

        // Collect winners from sub-agents
        $subAgents = \App\Models\SubAgent::all();
        foreach ($subAgents as $subAgent) {
            $orders = \App\Models\Order::where('status', 'accepted')
                ->when($period, function ($q) use ($period) {
                    if ($period->start_date && $period->end_date) {
                        $q->whereBetween('order_date', [$period->start_date, $period->end_date]);
                    }
                })
                ->whereHas('detail', function ($q) use ($subAgent) {
                    $q->where('sub_agent_id', $subAgent->id);
                })
                ->get();

            $totalProduct = 0;
            foreach ($orders as $order) {
                foreach ($order->detail->where('sub_agent_id', $subAgent->id) as $detail) {
                    $totalProduct += (int) $detail->qty;
                }
            }

            if ($totalProduct >= $reward->target_qty) {
                $winners[] = [
                    'type' => 'sub_agent',
                    'id' => $subAgent->id,
                    'name' => $subAgent->name,
                    'agent_id' => $subAgent->agent_id,
                    'total_product' => $totalProduct,
                ];
            }
        }

        return view('cms.admin.rewards.detail', compact('reward', 'winners'));
    }

    public function create()
    {
        $periods = Period::where('end_date', '>=', now())->get();
        return view('cms.admin.rewards.create', compact('periods'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'period_id' => 'required',
                'title' => 'required', 
                'target_qty' => 'required',
                'description' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $reward = Reward::create($request->all());

            if ($request->hasFile('image')) {
                $imageName = 'reward_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                Storage::disk('public')->put('images/reward/' . $imageName, $request->file('image')->getContent());
                $reward->image = $imageName;
                $reward->save();
            }

            return redirect()->route('rewards.index')->with('success', 'Reward created successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create reward: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Reward $reward)
    {
        $periods = Period::where('end_date', '>=', now())->get();
        return view('cms.admin.rewards.edit', compact('reward', 'periods'));
    }

    public function update(Request $request, Reward $reward)
    {
        try {
            $request->validate([
                'period_id' => 'required',
                'title' => 'required',
                'target_qty' => 'required', 
                'description' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $reward->update($request->except('image'));

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($reward->image) {
                    $oldImagePath = 'images/reward/' . $reward->image;
                    if (Storage::disk('public')->exists($oldImagePath)) {
                        Storage::disk('public')->delete($oldImagePath);
                    }
                }

                // Upload new image
                $imageName = 'reward_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                Storage::disk('public')->put('images/reward/' . $imageName, $request->file('image')->getContent());
                $reward->image = $imageName;
                $reward->save();
            }

            return redirect()->route('rewards.index')->with('success', 'Reward updated successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update reward: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Reward $reward)
    {
        try {
            // Delete image if exists
            if ($reward->image) {
                $imagePath = 'images/reward/' . $reward->image;
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            $reward->delete();
            return redirect()->route('rewards.index')->with('success', 'Reward deleted successfully');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete reward: ' . $e->getMessage());
        }
    }
}
