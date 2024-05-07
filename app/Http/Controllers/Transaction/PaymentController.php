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

    public function storePaymentImage(Request $request, Order $order, Payment $payment)
    {
        // dd($request->all(), $order);
        $validator = Validator::make($request->all(),[
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        if($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $order, $payment) {

            if ($request->hasFile('image')) { // Check if a new image is uploaded
                $imageName = 'payment_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $path = 'images/payment/' . $order->order_number . '/';

                // Delete existing image if present
                if ($payment && Storage::disk('public')->exists($path . $payment->image)) {
                    Storage::disk('public')->delete($path . $payment->image);
                }

                Storage::disk('public')->put($path . $imageName, $request->file('image')->getContent());
            }

            // Update or create payment record
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

    public function paymentGateWay(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'pay' => ['required', 'numeric'],
        ]);

        if($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $order, &$payment) {
                $payment = new Payment();
                $payment->order_id = $order->id;
                $payment->pay = $request->pay;
                $payment->remaining_payment = $order->total_price - $request->pay;
                $payment->save();


                // Set your Merchant Server Key
                \Midtrans\Config::$serverKey = config('midtrans.serverKey');
                // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
                \Midtrans\Config::$isProduction = false;
                // Set sanitization on (default)
                \Midtrans\Config::$isSanitized = true;
                // Set 3DS transaction for credit card to true
                \Midtrans\Config::$is3ds = true;

                $params = array(
                    'transaction_details' => array(
                        'order_id' => $payment->id,
                        'gross_amount' => $request->pay,
                    ),
                    'customer_details' => array(
                        'first_name' => Auth::user()->agentProfile->name ,
                        'email' => Auth::user()->email,
                    ),

                );

                $snapToken = \Midtrans\Snap::getSnapToken($params);

                $payment->snap_token = $snapToken;
                $payment->save();
            });

            return redirect()->route('payment.detail', $payment);
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function paymentDetail(Payment $payment)
    {
        return view('cms.transactions.detail-payment', compact('payment'));
    }

    // public function storePayment(Request $request, Order $order)
    // {
    //     try {
    //         DB::transaction(function () use ($request, $order) {
    //             $payment = new Payment();
    //             $payment->order_id = $order->id;
    //             $payment->
    //         });
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //     }
    // }
}
