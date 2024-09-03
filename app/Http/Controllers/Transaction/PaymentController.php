<?php

namespace App\Http\Controllers\Transaction;

use App\Models\Order;
use App\Models\Payment;
// use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\GenerateRandomString;
use App\Mail\NotificationPayment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{




    public function changePaymentStatus(Request $request, Payment $payment)
    {
        // return response()->json([
        //     'data' => $request->all(),
        //     'payment' => $payment
        // ]);

        try {
            DB::transaction(function () use ($request, $payment) {
                if ($request->status == 'success') {
                    $payment->status = 'success';
                    $payment->save();

                    if ($payment->remaining_payment == 0) {
                        $payment->order->payment_status = 'paid';
                        $payment->order->save();
                    }
                } else {
                    $payment->status = 'reject';
                    $payment->description = $request->description;
                    $payment->save();
                }

            });
            return redirect()->route('order.show', $payment->order)->with('success', 'Pembayaran Diterima');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function storePayment(Request $request, Order $order)
    {
        // return response()->json($request->all());

        $validator = Validator::make($request->all(), [
            'pay' => ['required', 'numeric'],
            'method' => ['required', 'string'],
            'bank' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
            'date' => ['required', 'date'],
        ]);

        if($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $order) {
                $existingPayment = $order->payment->sortByDesc('created_at')->first();
                $paymentCount = Payment::where('order_id', $order->id)->count();
                $invoiceNumber = 'INV'.GenerateRandomString::make(5) . ($paymentCount + 1) . now()->format('dmY');


                $payment = new Payment([
                    'order_id' => $order->id,
                    'invoice_number' => $invoiceNumber,
                    'pay' => $request->pay,
                    // 'remaining_payment' => $existingPayment ? $existingPayment->remaining_payment - $request->pay : $order->total_price - $request->pay,
                    'method' => $request->method,
                    'bank' => $request->bank ?? '',
                    // 'installment' => $existingPayment ? $existingPayment->installment + 1 : 1,
                    'note' => $request->note,
                    'date' => $request->date
                ]);


                if ($payment->save()) {
                    $order->payment_status = 'pending';
                    $order->save();
                }


                if ($payment->remaining_payment == 0) {
                    $order->payment_status = 'paid';
                    $order->save();
                }

                Mail::to($order->agent->email)->send(new NotificationPayment($payment));
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

                $order = Order::find($payment->order_id);
                $countPayment = Payment::where('order_id', $payment->order_id)->count();
                if ($countPayment == 0) {
                    $order->payment_status = 'unpaid';
                    $order->save();
                } else {
                    $order->payment_status = 'pending';
                    $order->save();
                }
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

    public function check (Order $order)
    {
        $existingPayment = $order->payment->sortByDesc('created_at')->first();

        return response()->json([
            'check' => $existingPayment
        ]);
    }
}
