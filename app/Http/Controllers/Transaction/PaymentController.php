<?php

namespace App\Http\Controllers\Transaction;

use App\Models\User;
use App\Models\Order;
// use Illuminate\Routing\Controller;
use App\Models\Package;
use App\Models\Payment;
use App\Models\BankOwner;
use App\Exports\ExportDatas;
use Illuminate\Http\Request;
use App\Helpers\TableFilter;
use App\Helpers\ValidateRole;
use App\Mail\NotificationPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\GenerateRandomString;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /** Daftar agen beserta pesanan accepted/stop, dipakai halaman Pembayaran dan Dashboard admin. */
    public static function agentPaymentQuery(Request $request): array
    {
        $query = User::role('agent');

        // Periode & status membatasi agen yang tampil sekaligus pesanan yang dijumlahkan per agen.
        $filters = TableFilter::apply($query, $request, [
            'period' => ['label' => 'Periode', 'type' => 'period', 'apply' => null],
            'payment' => [
                'label' => 'Status Pembayaran',
                'type' => 'select',
                'options' => ['paid' => 'Lunas', 'pending' => 'Dicicil', 'unpaid' => 'Belum Lunas'],
                'apply' => null,
            ],
        ]);
        $periodId = $filters['period']['value'];
        $orderScope = function ($q) use ($periodId) {
            $q->whereIn('status', ['accepted', 'stop']);
            if ($periodId !== 'all') {
                TableFilter::byOrderPeriod()($q, $periodId);
            }
        };

        $query->whereHas('order', $orderScope)
            ->with([
                'agentProfile',
                'order' => function ($q) use ($orderScope) {
                    $orderScope($q);
                    $q->with('detail.product.package.package.period')
                        ->withSum(['payment as paid_total' => function ($pq) {
                            $pq->where('status', 'accepted');
                        }], 'pay');
                }
            ]);

        // Sama dengan label di view: Lunas bila semua pesanan lunas, Dicicil bila ada yang dicicil.
        $withPaymentStatus = fn ($status, $not = false) => fn ($q) => $q->where(function ($o) use ($orderScope, $status, $not) {
            $orderScope($o);
            $o->where('payment_status', $not ? '!=' : '=', $status);
        });
        match ($filters['payment']['value']) {
            'paid' => $query->whereDoesntHave('order', $withPaymentStatus('paid', true)),
            'pending' => $query->whereHas('order', $withPaymentStatus('pending')),
            'unpaid' => $query->whereHas('order', $withPaymentStatus('paid', true))->whereDoesntHave('order', $withPaymentStatus('pending')),
            default => null,
        };

        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->whereHas('agentProfile', function ($ap) use ($search) {
                $ap->where('name', 'like', "%{$search}%");
            });
        }

        return [$query, $filters, $orderScope];
    }

    private static function orderPaymentFilters(): array
    {
        return [
            'period' => ['label' => 'Periode', 'type' => 'period', 'apply' => TableFilter::byOrderPeriod()],
            'payment_status' => [
                'label' => 'Status Pembayaran',
                'type' => 'select',
                'options' => ['unpaid' => 'Belum Dibayar', 'pending' => 'Dicicil', 'paid' => 'Lunas'],
            ],
            'order_date' => ['label' => 'Tanggal Pesanan', 'type' => 'date_range'],
        ];
    }

    public function index(Request $request)
    {
        $perPages = $request->get('perPage') ?? 10;

        if (ValidateRole::check('agent')) {
            $user = Auth::user();

            $query = Order::with('detail.product.package.package.period')
                ->withSum(['payment as paid_total' => function ($query) {
                    $query->where('status', 'accepted');
                }], 'pay')
                ->where('agent_id', $user->id)
                ->whereIn('status', ['accepted', 'stop'])
                ->orderByDesc('created_at');

            if ($request->filled('search')) {
                $search = trim($request->get('search'));
                $query->where('order_number', 'like', "%{$search}%");
            }

            $filters = TableFilter::apply($query, $request, self::orderPaymentFilters());

            if ($perPages == 'all') {
                $orders = (clone $query)->take(100)->get();
            } else {
                $perPage = min(max(intval($perPages), 1), 100);
                $orders = (clone $query)->paginate($perPage)->withQueryString();
            }

            return view('cms.agen.payment.index', compact('orders', 'user', 'filters'));
        } else {
            $perPages = $request->get('perPage') ?? 10;

            [$query, $filters, $orderScope] = self::agentPaymentQuery($request);


            if ($perPages == 'all') {
                $orders = (clone $query)->take(100)->get();
            } else {
                $perPage = min(max(intval($perPages), 1), 100);
                $orders = (clone $query)->paginate($perPage)->withQueryString();
            }

            if ($request->get('export') == 'true') {
                $datas = Order::with('agent.agentProfile')->whereIn('agent_id', (clone $query)->select('users.id'))->where($orderScope)->get()->groupBy('agent_id')->map(function ($item) {
                    $allPaid = $item->every(function ($o) {
                        return $o->payment_status === 'paid';
                    });
                    $hasPending = $item->contains(function ($o) {
                        return $o->payment_status === 'pending';
                    });
                    return [
                        'agent' => $item->first()->agent->agentProfile->name ?? 'N/A',
                        'total_price' => $item->sum('total_price'),
                        'status' => $allPaid ? 'Lunas' : ($hasPending ? 'Dicicilkan' : 'Belum Lunas')
                    ];
                });

                $headers = [
                    'agent',
                    'total_price',
                    'status',
                ];
                // return response()->json($datas);
                return Excel::download(new ExportDatas($datas, 'Data Pembayaran', $headers), 'Data Pembayaran.xlsx');
            }

            return view('cms.admin.payment.index', compact('orders', 'filters'));
        }
    }

    public function show(User $user, Request $request)
    {
        $perPages = $request->get('perPage') ?? 10;

        $packages = Package::with('product')->whereHas('period', function ($query) {
            $query->where('is_active', 1);
        })->get();

        $query = Order::with(['agent.agentProfile', 'detail.product.package.package.period'])
            ->withSum(['payment as paid_total' => function ($query) {
                $query->where('status', 'accepted');
            }], 'pay')
            ->where('agent_id', $user->id)
            ->whereIn('status', ['accepted', 'stop']);

        $filters = TableFilter::apply($query, $request, self::orderPaymentFilters());

        if ($perPages == 'all') {
            $orders = (clone $query)->take(100)->get();
        } else {
            $perPage = min(max(intval($perPages), 1), 100);
            $orders = (clone $query)->paginate($perPage)->withQueryString();
        }

        if ($request->get('export') == 'true') {
            $datas = (clone $query)->get()->map(function ($item) {
                return [
                    'order_number' => $item->order_number,
                    'order_date' => $item->order_date,
                    'total_price' => $item->total_price,
                    'status' => $item->payment_status === 'paid' ? 'Lunas' : ($item->payment_status === 'pending' ? 'Dicicil' : 'Belum Dibayar')
                ];
            });

            // if ($request->get('type') == 'json') {
                // return response()->json($datas);
            // }

            $headers = [
                'order_number',
                'order_date',
                'total_price',
                'status',
            ];
            return Excel::download(new ExportDatas($datas, 'Data Pembayaran Paket #' . $user->agentProfile->name, $headers), 'Data Pembayaran Paket '.$user->agentProfile->name.' .xlsx');
        }

        return view('cms.admin.payment.show', compact('user', 'packages', 'orders', 'filters'));
    }

    public function showPaymentAgent(User $user, Order $order)
    {
        if (auth()->user()->hasRole('agent') && ($order->agent_id !== auth()->id() || $user->id !== auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $packages = Package::with('product')->whereHas('period', function ($query) {
            $query->where('is_active', 1);
        })->get();

        $banks = BankOwner::all();


        return view('cms.agen.payment.detail', compact('packages', 'order', 'user', 'banks'));
    }

    public function showPayment(Request $request, User $user, Order $order)
    {
        $packages = Package::with('product')->whereHas('period', function ($query) {
            $query->where('is_active', 1);
        })->get();

        $banks = BankOwner::all();

        if ($request->get('export') == 'true') {
            $datas = $order->payment->map(function ($item) {
                return [
                    'payment_date' => $item->date,
                    'metode' => $item->method,
                    'total_payment' => $item->pay,
                    'status' => $item->status === 'accepted' ? 'Diterima' : ($item->status === 'rejected' ? 'Ditolak' : 'Pending'),
                    'note' => strip_tags($item->note),
                ];
            });

            $headers = [
                'payment_date',
                'metode',
                'total_payment',
                'status',
                'note',
            ];

            return Excel::download(new ExportDatas($datas, 'Data Pembayaran #'.$user->agentProfile->name.' #Order '.$order->order_number, $headers), 'Data Pembayaran '.$user->agentProfile->name.' Order '.$order->order_number.'.xlsx');
        }

        return view('cms.admin.payment.detail', compact('packages', 'order', 'user', 'banks'));
    }

    public function changePaymentStatus(Request $request, Payment $payment)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'super_admin', 'finance_admin'])) {
            abort(403, 'Unauthorized');
        }

        try {
            DB::transaction(function () use ($request, $payment) {
                $payment->status = $request->status;
                $payment->save();

                $order = Order::find($payment->order_id);
                $order->recalculatePaymentStatus();
            });
            return redirect()->back()->with('success', 'Status Pembayaran Berhasil Diubah');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function changePaymentVerify(Request $request, Payment $payment)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'super_admin', 'finance_admin'])) {
            abort(403, 'Unauthorized');
        }

        try {
            DB::transaction(function () use ($request, $payment) {
                $payment->is_confirmed = !$payment->is_confirmed;
                $payment->save();
            });
            return redirect()->back()->with('success', 'Status Pembayaran Berhasil Diverifikasi');
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
        if ($order->total_price <= 0) {
            return back()->with('error', 'Tidak dapat menambahkan pembayaran untuk pesanan dengan total tagihan Rp 0.');
        }

        if (in_array($order->status, ['canceled', 'reject'])) {
            return back()->with('error', 'Tidak dapat menambahkan pembayaran untuk pesanan yang dibatalkan atau ditolak.');
        }

        $validator = Validator::make($request->all(), [
            'pay' => ['required', 'numeric'],
            'method' => ['required', 'string'],
            'bank' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $order, &$payment) {
                $paymentCount = Payment::where('order_id', $order->id)->count();
                $invoiceNumber = 'INV' . GenerateRandomString::make(5) . ($paymentCount + 1) . now()->format('dmY');
                $bank = BankOwner::where('id', $request->bank)->first();

                $photoName = null;
                if ($request->hasFile('photo')) {
                    $photoName = 'payment_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
                    Storage::disk('public')->put('images/proofs/' . $photoName, $request->file('photo')->getContent());
                }

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
                    'date' => $request->date,
                    'status' => 'accepted',
                    'photo' => $photoName
                ]);

                $payment->save();
                $order->recalculatePaymentStatus();

                // Mail::to($order->agent->email)->send(new NotificationPayment($payment));
            });

            try {
                Mail::to($order->agent->email)->send(new NotificationPayment($payment));
            } catch (\Exception $e) {
                // Log error email tapi jangan hentikan proses redirect sukses
                Log::error("Email gagal kirim: " . $e->getMessage());
            }
            return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function storePaymentAgent(Request $request, Order $order)
    {
        if (auth()->user()->hasRole('agent') && $order->agent_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($order->total_price <= 0) {
            return back()->with('error', 'Tidak dapat menambahkan pembayaran untuk pesanan dengan total tagihan Rp 0.');
        }

        if (in_array($order->status, ['canceled', 'reject'])) {
            return back()->with('error', 'Tidak dapat menambahkan pembayaran untuk pesanan yang dibatalkan atau ditolak.');
        }

        $validator = Validator::make($request->all(), [
            'pay' => ['required', 'numeric'],
            'method' => ['required', 'string'],
            'bank' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $order, &$payment) {
                $paymentCount = Payment::where('order_id', $order->id)->count();
                $invoiceNumber = 'INV' . GenerateRandomString::make(5) . ($paymentCount + 1) . now()->format('dmY');
                $bank = BankOwner::where('id', $request->bank)->first();

                $photoName = 'payment_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
                Storage::disk('public')->put('images/proofs/' . $photoName, $request->file('photo')->getContent());

                $payment = new Payment([
                    'order_id' => $order->id,
                    'invoice_number' => $invoiceNumber,
                    'pay' => $request->pay,
                    'method' => $request->method,
                    'bank' => $bank->name ?? null,
                    'bank_owner_id' => $request->bank ?? null,
                    'note' => $request->note,
                    'date' => $request->date,
                    'status' => 'pending',
                    'photo' => $photoName
                ]);

                $payment->save();
                $order->recalculatePaymentStatus();

                // Mail::to($order->agent->email)->send(new NotificationPayment($payment));
            });

            try {
                Mail::to($order->agent->email)->send(new NotificationPayment($payment));
            } catch (\Exception $e) {
                // Log error email tapi jangan hentikan proses redirect sukses
                Log::error("Email gagal kirim: " . $e->getMessage());
            }
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
                if (!empty($payment->photo)) {
                    $imagePath = storage_path('app/public/images/proofs/' . $payment->photo);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $order = Order::find($payment->order_id);
                $payment->delete();
                $order->recalculatePaymentStatus();
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
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $payment) {
                if ($request->hasFile('photo')) {
                    if ($payment->photo && file_exists(storage_path('app/public/images/proofs/' . $payment->photo))) {
                        unlink(storage_path('app/public/images/proofs/' . $payment->photo));
                    }

                    $imageName = 'proofs_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
                    Storage::disk('public')->put('images/proofs/' . $imageName, $request->file('photo')->getContent());

                    $payment->photo = $imageName;
                }

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

    public function updatePaymentAgent(Request $request, Payment $payment)
    {
        if (auth()->user()->hasRole('agent') && $payment->order?->agent_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'upd_method' => ['required', 'string'],
            'upd_bank' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $payment) {
                if ($request->hasFile('photo')) {
                    if ($payment->photo && file_exists(storage_path('app/public/images/proofs/' . $payment->photo))) {
                        unlink(storage_path('app/public/images/proofs/' . $payment->photo));
                    }

                    $imageName = 'proofs_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
                    Storage::disk('public')->put('images/proofs/' . $imageName, $request->file('photo')->getContent());

                    $payment->photo = $imageName;
                }

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
