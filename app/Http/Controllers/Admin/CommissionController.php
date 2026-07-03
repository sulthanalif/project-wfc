<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Package;
use App\Models\OrderDetail;
use App\Models\SubAgent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPages = $request->get('perPage') ?? 5;

        if ($perPages == 'all') {
            $commissions = Commission::whereHas('package.period', function ($query) {
                $query->where('is_active', 1);
            })->latest()->get();
        } else {
            $perPage = intval($perPages);
            $commissions = Commission::whereHas('package.period', function ($query) {
                $query->where('is_active', 1);
            })->latest()->paginate($perPage);
        }

        return view('cms.admin.commissions.index', compact('commissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $packages = Package::whereHas('period', function ($query) {
            $query->where('is_active', 1);
        })->get();

        return view('cms.admin.commissions.create', compact('packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'package_id' => 'required|exists:packages,id',
            'term' => 'required|integer|min:1',
            'reward' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, &$commission) {
                $commission = Commission::create([
                    'title' => $request->input('title'),
                    'package_id' => $request->input('package_id'),
                    'term' => $request->input('term'),
                    'reward' => $request->input('reward'),
                    'description' => $request->input('description'),
                ]);
            });

            if ($commission) {
                return redirect()->route('commissions.index')->with('success', 'Komisi berhasil ditambahkan.');
            } else {
                return redirect()->back()->with('error', 'Gagal menambahkan komisi. Silakan coba lagi.');
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
    public function show(Commission $commission)
    {
        $packageId = $commission->package_id;
        $termQty = (int) $commission->term;
        $winners = [];

        // Check if reward is percentage
        $isPercentage = strpos($commission->reward, '%') !== false;
        $bonusPercentage = 0;
        $bonusValue = 0;

        if ($isPercentage) {
            preg_match('/([0-9.]+)/', $commission->reward, $matches);
            $bonusPercentage = isset($matches[1]) ? floatval($matches[1]) : 0;
        } else {
            // Try to convert reward to numeric value
            $bonusValue = floatval(preg_replace('/[^0-9.]/', '', $commission->reward)) ?: 0;
        }

        $agents = User::role('agent')->where('active', 1)->with('agentProfile')->get();
        foreach ($agents as $agent) {
            $agentName = optional($agent->agentProfile)->name ?? $agent->email;

            // Get agent's direct orders
            $agentOrderDetails = OrderDetail::whereHas('order', function ($query) use ($agent) {
                    $query->where('status', 'accepted')
                        ->where('agent_id', $agent->id);
                })
                ->whereHas('product.package.package', function ($query) use ($packageId) {
                    $query->where('id', $packageId)
                        ->whereHas('period', function ($query) {
                            $query->where('is_active', 1);
                        });
                })
                ->with('product')
                ->get();

            // Get all sub-agents under this agent and their orders
            $subAgentIds = SubAgent::where('agent_id', $agent->id)->pluck('id');
            $subAgentOrderDetails = OrderDetail::whereIn('sub_agent_id', $subAgentIds)
                ->whereHas('order', function ($query) {
                    $query->where('status', 'accepted');
                })
                ->whereHas('product.package.package', function ($query) use ($packageId) {
                    $query->where('id', $packageId)
                        ->whereHas('period', function ($query) {
                            $query->where('is_active', 1);
                        });
                })
                ->with('product')
                ->get();

            // Combine both
            $allOrderDetails = $agentOrderDetails->concat($subAgentOrderDetails);

            $totalProduct = $allOrderDetails->sum('qty');
            $totalPrice = $allOrderDetails->sum(function ($detail) {
                return $detail->sub_price * $detail->qty;
            });

            if ($totalProduct >= $termQty) {
                if ($isPercentage) {
                    $totalBonus = $totalPrice * $bonusPercentage / 100;
                } else {
                    $totalBonus = $bonusValue;
                }

                $winners[] = [
                    'name' => $agentName,
                    'total_product' => $totalProduct,
                    'total_bonus' => $totalBonus,
                ];
            }
        }

        usort($winners, function ($a, $b) {
            return $b['total_product'] <=> $a['total_product'];
        });

        return view('cms.admin.commissions.detail', compact('commission', 'winners'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commission $commission)
    {
        $packages = Package::whereHas('period', function ($query) {
            $query->where('is_active', 1);
        })->get();

        return view('cms.admin.commissions.edit', compact('commission', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commission $commission)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'package_id' => 'required|exists:packages,id',
            'term' => 'required|integer|min:1',
            'reward' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $commission) {
                $commission->update([
                    'title' => $request->input('title'),
                    'package_id' => $request->input('package_id'),
                    'term' => $request->input('term'),
                    'reward' => $request->input('reward'),
                    'description' => $request->input('description'),
                ]);
            });

            return redirect()->route('commissions.index')->with('success', 'Komisi berhasil diperbarui.');
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
    public function destroy(Commission $commission)
    {
        try {
            $commission->delete();
            return redirect()->route('commissions.index')->with('success', 'Komisi berhasil dihapus.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus komisi: ' . $e->getMessage());
        }
    }

    public function archive(Request $request)
    {
        $perPages = $request->get('perPage') ?? 5;

        if ($perPages == 'all') {
            $commissions = Commission::whereHas('package.period', function ($query) {
                $query->where('is_active', 0);
            })->latest()->get();
        } else {
            $perPage = intval($perPages);
            $commissions = Commission::whereHas('package.period', function ($query) {
                $query->where('is_active', 0);
            })->latest()->paginate($perPage);
        }

        return view('cms.admin.commissions.archive', compact('commissions'));
    }

    public function archiveShow(Commission $commission)
    {
        $packageId = $commission->package_id;
        $termQty = (int) $commission->term;
        $winners = [];

        // Check if reward is percentage
        $isPercentage = strpos($commission->reward, '%') !== false;
        $bonusPercentage = 0;
        $bonusValue = 0;

        if ($isPercentage) {
            preg_match('/([0-9.]+)/', $commission->reward, $matches);
            $bonusPercentage = isset($matches[1]) ? floatval($matches[1]) : 0;
        } else {
            // Try to convert reward to numeric value
            $bonusValue = floatval(preg_replace('/[^0-9.]/', '', $commission->reward)) ?: 0;
        }

        $agents = User::role('agent')->where('active', 1)->with('agentProfile')->get();
        foreach ($agents as $agent) {
            $agentName = optional($agent->agentProfile)->name ?? $agent->email;

            // Get agent's direct orders
            $agentOrderDetails = OrderDetail::whereHas('order', function ($query) use ($agent) {
                    $query->where('status', 'accepted')
                        ->where('agent_id', $agent->id);
                })
                ->whereHas('product.package.package', function ($query) use ($packageId) {
                    $query->where('id', $packageId)
                        ->whereHas('period', function ($query) {
                            $query->where('is_active', 0);
                        });
                })
                ->with('product')
                ->get();

            // Get all sub-agents under this agent and their orders
            $subAgentIds = SubAgent::where('agent_id', $agent->id)->pluck('id');
            $subAgentOrderDetails = OrderDetail::whereIn('sub_agent_id', $subAgentIds)
                ->whereHas('order', function ($query) {
                    $query->where('status', 'accepted');
                })
                ->whereHas('product.package.package', function ($query) use ($packageId) {
                    $query->where('id', $packageId)
                        ->whereHas('period', function ($query) {
                            $query->where('is_active', 0);
                        });
                })
                ->with('product')
                ->get();

            // Combine both
            $allOrderDetails = $agentOrderDetails->concat($subAgentOrderDetails);

            $totalProduct = $allOrderDetails->sum('qty');
            $totalPrice = $allOrderDetails->sum(function ($detail) {
                return $detail->sub_price * $detail->qty;
            });

            if ($totalProduct >= $termQty) {
                if ($isPercentage) {
                    $totalBonus = $totalPrice * $bonusPercentage / 100;
                } else {
                    $totalBonus = $bonusValue;
                }

                $winners[] = [
                    'name' => $agentName,
                    'total_product' => $totalProduct,
                    'total_bonus' => $totalBonus,
                ];
            }
        }

        usort($winners, function ($a, $b) {
            return $b['total_product'] <=> $a['total_product'];
        });

        return view('cms.admin.commissions.archive-detail', compact('commission', 'winners'));
    }

}
