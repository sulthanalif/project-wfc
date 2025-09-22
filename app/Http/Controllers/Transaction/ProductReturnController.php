<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\GenerateRandomString;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\User;
use Illuminate\Http\Request;

class ProductReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $returns = ProductReturn::with(['user', 'order', 'product', 'subProduct'])
            ->orderBy('date_in', 'desc')
            ->get();

        return view('cms.return.index', compact('returns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $returns = ProductReturn::all()->count();
        $returnNumber = GenerateRandomString::make(8) . now()->format('dmY') . '-' . ($returns + 1);

        if (!$user->hasRole('agent')) {
            $agents = User::whereHas('roles', function ($query) {
                $query->where('name', 'agent');
            })
                ->where('active', 1)
                ->where('email_verified_at', '!=', null)
                ->where('email_verified_at', '!=', '')
                ->get();
        }

        $orders = Order::with('detail')
            ->where('status', '!=', 'pending')
            ->where('status', '!=', 'reject')
            ->where('status', '!=', 'canceled')
            // ->where('delivery_status', 'success')
            ->get();

        $products = Product::with(['detail', 'subProduct'])->get();

        return view('cms.return.partial.create', compact(['agents', 'returnNumber', 'orders', 'products']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $products = $request->products;
        dd($products);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
