<?php

namespace App\Http\Controllers\Transaction;

use App\Models\Order;
use App\Models\Payment;
// use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $roleUser = $user->roles->first();
        $roleName = $roleUser->name;

        if ($roleName !== 'agent'){
            return view('');
        }
    }

    public function storePayment(Request $request, Order $order)
    {
        // dd($request->all(), $order);
        $validator = Validator::make($request->all(),[
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        if($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $order, &$store) {
                $imageName = 'payment_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                Storage::disk('public')->put('images/payment/'. $order->agent_id . '/' . $imageName, $request->file('image')->getContent());

                $store = Payment::create([
                    'order_id' => $order->id,
                    'image' => $imageName
                ]);
            });
            if ($store) {
                return redirect()->route('order.show', $order)->with('success', 'Bukti pembayaran telah ditambhakan');
            } else {
                return back()->with('error', 'Bukti pembayaran gagal ditambahkan');
            }
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function changePaymentStatus(Order $order)
    {
        if ($order) {
            try {
                DB::transaction(function () use ($order, &$update) {
                    $update = $order->update([
                        'payment_status' => $order->payment_status == 'unpaid' ? 'paid' : 'unpaid'
                    ]);
                });
                if ($update) {
                    return back()->with('success', 'Status pembayaran berhasil diubah');
                } else {
                    return back()->with('error', 'Status pembayaran gagal diubah');
                }
            } catch (\Throwable $th) {
                $data = [
                    'message' => $th->getMessage(),
                    'status' => 400
                ];
                return view('cms.error', compact('data'));
            }
        }
    }
}
