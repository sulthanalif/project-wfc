<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\GenerateRandomString;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Package;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
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
            $orders = Order::where('agent_id', $user->id)->paginate(10);

            return view('cms.agen.order.index', compact('orders'));
        } else {
            $orders = Order::paginate(10);

            return view('cms.admin.order.index', compact('orders'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::all()->count();
        $orderNumber = GenerateRandomString::make(8) . now()->format('dmY') . '-' . ($orders + 1);

        $packages = Package::with('product')->get();

        $user = Auth::user();
        $roleUser = $user->roles->first();
        $roleName = $roleUser->name;

        if ($roleName == 'agent') {
            return view('cms.transactions.create', [
                'orderNumber' => $orderNumber,
                'packages' => $packages
            ]);
        } else {
            return view('cms.transactions.create', [
                'agents' => User::role('agent')->get(),
                'orderNumber' => $orderNumber,
                'packages' => $packages
            ]);
        }


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => ['required'],
            'order_number' => ['required'],
            'total_price' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, &$store) {
                $store = Order::create([
                    'agent_id' => $request->agent_id,
                    'order_number' => $request->order_number,
                    'total_price' => $request->total_price,
                    'order_date' => now(),
                ]);
            });
            if ($store) {
                return redirect()->route('order.index')->with('success', 'Pesanan Telah Dibuat');
            } else {
                return redirect()->route('order.index')->with('error', 'Pesanan Gagal Dibuat');
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
    public function show(Order $order)
    {
        return view('cms.transactions.detail', $order);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
