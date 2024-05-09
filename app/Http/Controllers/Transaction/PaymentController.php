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

    public function storePaymentImage(Request $request, Order $order)
    {
        // dd($request->all(), $order);
        $validator = Validator::make($request->all(),[
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        if($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $order) {
            $existingPayment = $order->payment; // Check if payment exists

            $imageName = null; // Initialize image name to null

            if ($request->hasFile('image')) { // Check if a new image is uploaded
                $imageName = 'payment_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $path = 'images/payment/' . $order->agent_id . '/';

                // Delete existing image if present
                if ($existingPayment && Storage::disk('public')->exists($path . $existingPayment->image)) {
                    Storage::disk('public')->delete($path . $existingPayment->image);
                }

                Storage::disk('public')->put($path . $imageName, $request->file('image')->getContent());
            }

            // Update or create payment record
            $payment = $existingPayment ? $existingPayment : new Payment();
            $payment->order_id = $order->id;
            $payment->image = $imageName;
            $payment->save();
        });

        return redirect()->route('order.show', $order)->with('success', 'Bukti pembayaran telah diubah');
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

    public function storePayment(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'pay' => ['required', 'numeric']
        ]);

        if($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $order) {
                $payment = new Payment();
                $payment->order_id = $order->id;
                $payment->pay = $request->pay;
                $payment->remaining_payment = $order->payment->sortByDesc('created_at')->first() ? $order->payment->sortByDesc('created_at')->first()->remaining_payment - $request->pay  : $order->total_price - $request->pay;
                $payment->status = 'success';
                $payment->save();

                if ($payment->remaining_payment == 0) {
                    $order->payment_status = 'paid';
                    $order->save();
                }
            });
            return redirect()->route('order.show', $order)->with('success' , 'Pembayaran berhasil ditambahkan');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function destroy(Request $request, Payment $payment)
    {
        try {
            DB::transaction(function () use ($request, $payment) {
                $payment->delete();
            });
            return back()->with('success', 'Berhasil dihapus');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }
}
