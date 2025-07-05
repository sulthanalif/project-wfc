<?php

namespace App\Http\Controllers\Transaction;

use App\Models\User;
use App\Models\Order;
// use Illuminate\Routing\Controller;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Helpers\ValidateRole;
use App\Mail\NotificationPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Helpers\GenerateRandomString;
use App\Models\BankOwner;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $perPages = $request->get('perPage') ?? 10;

        if (ValidateRole::check('agent')) {
            if ($perPages == 'all') {
                $orders = Order::where('agent_id', Auth::user()->id)->orderByDesc('created_at')->get();
            } else {
                $perPage = intval($perPages);
                $orders = Order::where('agent_id', Auth::user()->id)->orderByDesc('created_at')->paginate($perPage);
            }

            return view('cms.transactions.index', compact('orders'));
        } else {
            if ($perPages == 'all') {
                $orders = Order::with('agent.agentProfile')->get()->groupBy('agent_id');
            } else {
                $perPage = intval($perPages);
                $orders = Order::with('agent.agentProfile')->paginate($perPage)->groupBy('agent_id');
            }

            return view('cms.admin.payment.index', compact('orders'));
        }
    }

    public function show(User $user, Request $request)
    {
        $perPages = $request->get('perPage') ?? 10;

        $packages = Package::with('product')->whereHas('period', function ($query) {
            $query->where('is_active', 1);
        })->get();

        if ($perPages == 'all') {
            $orders = Order::with('agent.agentProfile')->where('agent_id', $user->id)->get();
        } else {
            $perPage = intval($perPages);
            $orders = Order::with('agent.agentProfile')->where('agent_id', $user->id)->paginate($perPage);
        }

        return view('cms.admin.payment.show', compact('user', 'packages', 'orders'));
    }

    public function showPayment(User $user, Order $order)
    {
        $packages = Package::with('product')->whereHas('period', function ($query) {
            $query->where('is_active', 1);
        })->get();

        $banks = BankOwner::all();

        return view('cms.admin.payment.detail', compact('packages', 'order', 'user', 'banks'));
    }

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

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $order) {
                $paymentCount = Payment::where('order_id', $order->id)->count();
                $invoiceNumber = 'INV' . GenerateRandomString::make(5) . ($paymentCount + 1) . now()->format('dmY');
                $bank = BankOwner::where('id', $request->bank)->first();

                $payment = new Payment([
                    'order_id' => $order->id,
                    'invoice_number' => $invoiceNumber,
                    'pay' => $request->pay,
                    // 'remaining_payment' => $existingPayment ? $existingPayment->remaining_payment - $request->pay : $order->total_price - $request->pay,
                    'method' => $request->method,
                    'bank' => $bank->name ?? null,
                    'bank_owner_id' => $request->bank ?? null,
                    // 'installment' => $existingPayment ? $existingPayment->installment + 1 : 1,
                    'note' => $request->note,
                    'date' => $request->date
                ]);


                if ($payment->save()) {
                    $order->payment_status = 'pending';
                    $order->save();
                }


                $existingPayment = $order->payment()->get();

                if ($existingPayment->sum('pay') == $order->total_price) {
                    $order->payment_status = 'paid';
                    $order->save();
                } else {
                    $order->payment_status = 'pending';
                    $order->save();
                }

                Mail::to($order->agent->email)->send(new NotificationPayment($payment));
            });
            return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan');
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
                    if ($order->payment()->sum('pay') == $order->total_price) {
                        $order->payment_status = 'paid';
                        $order->save();
                    } else {
                        $order->payment_status = 'pending';
                        $order->save();
                    }
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

    public function check(Order $order)
    {
        $existingPayment = $order->payment->sortByDesc('created_at')->first();

        return response()->json([
            'check' => $existingPayment
        ]);
    }

    public function updatePayment(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'upd_method' => ['required', 'string'],
            'upd_bank' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $payment) {
                $payment->method = $request->upd_method;

                if ($request->upd_method == 'Transfer') {
                    $payment->bank_owner_id = $request->upd_bank;
                } else {
                    $payment->bank_owner_id = null;
                    $payment->bank = null;
                }
                
                $payment->save();
            });
            return redirect()->back()->with('success', 'Pembayaran berhasil diubah');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }
}
