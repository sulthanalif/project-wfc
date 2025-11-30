<?php

namespace App\Http\Controllers\Transaction;

use App\Models\User;
use App\Models\Order;
use App\Models\Period;
use App\Models\Package;
use App\Models\Product;
use App\Models\AccessDate;
use App\Helpers\GetProduct;
use App\Models\OrderDetail;
use App\Exports\ExportDatas;
use Illuminate\Http\Request;
use App\Helpers\ValidateRole;
use App\Exports\InvoiceExport;
use Illuminate\Support\Carbon;
use App\Exports\DetailOrderExport;
use App\Mail\NotificationAccOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\GenerateRandomString;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPages = $request->get('perPage') ?? 5;
        $status = $request->get('status') ?? 'all';

        if (ValidateRole::check('agent')) {
            if ($perPages == 'all') {
                if ($status == 'all') {
                    $orders = Order::where('agent_id', Auth::user()->id)->orderByDesc('created_at')->get();
                } else {
                    $orders = Order::where('status', $status)->where('agent_id', Auth::user()->id)->orderByDesc('created_at')->get();
                }
            } else {
                if ($status == 'all') {
                    $perPage = intval($perPages);
                    $orders = Order::where('agent_id', Auth::user()->id)->orderByDesc('created_at')->paginate($perPage);
                } else {
                    $perPage = intval($perPages);
                    $orders = Order::where('status', $status)->where('agent_id', Auth::user()->id)->orderByDesc('created_at')->paginate($perPage);
                }
            }

            return view('cms.transactions.index', compact('orders'));
        } else {
            if ($perPages == 'all') {
                if ($status == 'all') {
                    $orders = Order::orderByDesc('created_at')->get();
                } else {
                    $orders = Order::where('status', $status)->orderByDesc('created_at')->get();
                }
            } else {
                if ($status == 'all') {
                    $perPage = intval($perPages);
                    $orders = Order::orderByDesc('created_at')->paginate($perPage);
                } else {
                    $perPage = intval($perPages);
                    $orders = Order::where('status', $status)->orderByDesc('created_at')->paginate($perPage);
                }
            }

            if ($request->get('export') == 'true') {

                $datas = Order::orderByDesc('created_at')->get()->map(function ($item) {
                    return [
                        'order_number' => $item->order_number,
                        'order_date' => $item->order_date,
                        'total_price' => $item->total_price,
                        'agent_name' => $item->agent->agentProfile->name,
                        'status' => $item->status,
                        'status_payment' => $item->payment_status,
                        'status_delivery' => $item->isAllItemDistributed() ? 'Sukses' :
                            ($item->distributions->isNotEmpty() ? 'Sedang Proses' : 'Belum Dikirim'),
                        'created_at' => $item->created_at,
                        // 'updated_at' => $item->updated_at,
                    ];
                });
                $headers = [
                    'order_number',
                    'order_date',
                    'total_price',
                    'agent_name',
                    'status',
                    'status_payment',
                    'status_delivery',
                    'created_at',
                    // 'updated_at',
                ];

                // return response()->json($datas);
                return Excel::download(new ExportDatas($datas, 'Data Pesanan', $headers), 'Data Pesanan.xlsx');
            }

            return view('cms.transactions.index', compact('orders'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::all()->count();
        $orderNumber = GenerateRandomString::make(8) . now()->format('dmY') . '-' . ($orders + 1);

        $packages = Package::with('product')->whereHas('period', function ($query) {
            $query->where('is_active', 1);
        })->get();

        $user = Auth::user();
        $roleUser = $user->roles->first();
        $roleName = $roleUser->name;

        if ($roleName == 'agent') {
            return view('cms.transactions.agent.create', [
                'agents' => $user,
                'orderNumber' => $orderNumber,
                'packages' => $packages
            ]);
        } else {
            return view('cms.transactions.create', [
                'agents' => User::role('agent')->where('active', 1)->get(),
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
        // return response()->json($request->all());
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'agent_id' => ['required'],
            'order_number' => ['required'],
            'order_date' => ['required'],
            'total_price' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        $user = Auth::user();
        $periode = Period::where('is_active', 1)->first();

        if ($user->roleName == 'agent') {
            if ($user->order()?->where('status', '!=', 'reject')->exists() && $user->order()?->where('status', '!=', 'reject')->first()->created_at < $periode->end_date) {
                return back()->with('error', 'Selesaikan dulu pesanan pada periode ini');
            }
        }

        $products = json_decode($request->products, true);

        if ($products == null) {
            return back()->with('error', 'Produk Tidak Boleh Kosong');
        }

        try {
            DB::transaction(function () use ($request, $products, &$order) {
                // $access_date = AccessDate::first();
                $order = new Order([
                    'agent_id' => $request->agent_id,
                    'order_number' => $request->order_number,
                    'total_price' => $request->total_price,
                    'order_date' => $request->order_date ? $request->order_date : now(),
                    // 'access_date' => $access_date->date ?? Carbon::parse($request->order_date)->addMonths(3),
                    'status' =>  ValidateRole::check('super_admin||admin') ? 'accepted' : 'pending',
                ]);

                $order->save();
                // Membuat OrderDetail untuk setiap produk

                // Group products by product_id and sub_agent_id, then merge quantities and subTotals
                $grouped = collect($products)->groupBy(function ($item) {
                    return $item['productId'] . '|' . $item['subAgentId'];
                })->map(function ($group) {
                    $first = $group->first();
                    return [
                        'productId'   => $first['productId'],
                        'subAgentId'  => $first['subAgentId'],
                        'qty'         => $group->sum('qty'),
                        'subTotal'    => $group->sum('subTotal'),
                    ];
                })->values()->toArray();

                foreach ($grouped as $product) {
                    // Mendapatkan detail produk berdasarkan productId
                    $productDetail = Product::findOrFail($product['productId']);

                    // Membuat OrderDetail untuk setiap produk
                    OrderDetail::create([
                        'order_id'    => $order->id,
                        'sub_agent_id'=> $product['subAgentId'],
                        'product_id'  => $product['productId'],
                        'sub_price'   => $product['subTotal'],
                        'qty'         => $product['qty']
                    ]);
                }

                // foreach ($products as $product) {
                //     // Mendapatkan detail produk berdasarkan productId
                //     $productDetail = Product::findOrFail($product['productId']);

                //     // Membuat OrderDetail untuk setiap produk
                //     OrderDetail::create([
                //         'order_id' => $order->id,
                //         // 'sub_agent_id' => $request->sub_agent_item,
                //         'sub_agent_id' => $product['subAgentId'],
                //         // 'name' => $productDetail->name,
                //         'product_id' => $product['productId'],
                //         // 'price' => $productDetail->price,
                //         'sub_price' => $product['subTotal'],
                //         'qty' => $product['qty']
                //     ]);
                // }
            });
            if ($order) {
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
    public function show(Request $request, Order $order)
    {

        // $product= GetProduct::detail('Product PHL 1');
        // return response()->json($product);
        $packages = Package::with('product')->whereHas('period', function ($query) {
            $query->where('is_active', 1);
        })->get();

        // $selectsProduct = $packages->pluck('product_id')->unique()->map(function ($productId) use ($packages) {
        //     $product = Product::where('id', $productId)->get();
        //     return $product->name;
        // })->toArray();

        // return response()->json($selectsProduct );

        $agents = auth()->user();

        //select agent name
        $selects = $order->detail()->pluck('sub_agent_id')->unique()->map(function ($subAgentId) use ($order) {
            $subAgent = $order->agent->subAgent->where('id', $subAgentId)->first();
            return $subAgent ? $subAgent->name : $order->agent->agentProfile->name;
        })->toArray();

        //select product
        $selectProducts = $order->detail->pluck('product_id')->unique()->map(function ($productId) use ($order) {
            $product = $order->detail->where('product_id', $productId)->first()->product;
            return [
                'id' => $product->id,
                'name' => $product->name
            ];
        })->toArray();

        // return response()->json($selectProducts);
        // if ($request->get('select')) {
        //     $order = $order->detail()->whereHas('subAgent', function ($query) use ($request) {
        //         $query->where('name', 'like', "%{$request->select}%");
        //     })->get();
        // }

        if ($request->get('export') == 'true') {
            return Excel::download(new DetailOrderExport($order->id), 'Order Detail ' . $order->order_number . '.xlsx');
        }

        return view('cms.transactions.detail', compact(['order', 'packages', 'agents', 'selects', 'selectProducts']));
    }



    public function accOrder(Order $order)
    {
        if ($order) {
            $update = $order->update([
                'status' => 'accepted'
            ]);

            if ($update) {
                return redirect()->back()->with('success', 'Order Berhasil diterima');
            } else {
                return back()->with('error', 'Kesalahan');
            }
        } else {
            return back()->with('error', 'Data Tidak Ditemukan');
        }
    }

    public function changeOrderStatus(Request $request, Order $order)
    {
        if ($order) {
            $update = $order->update([
                'status' => $request->status,
                'description' => $request->description
            ]);

            if ($update) {
                Mail::to($order->agent->email)->send(new NotificationAccOrder($order));

                return redirect()->back()->with('success', 'Order Status Berhasil Diubah');
            } else {
                return back()->with('error', 'Kesalahan');
            }
        } else {
            return back()->with('error', 'Data Tidak Ditemukan');
        }
    }

    public function getOrderStats()
    {
        try {
            $stats = [
                'total_income' => Order::sum('total_price'),
                'total_orders' => Order::count(),
                'total_paid' => Order::where('payment_status', 'paid')->count(),
                'total_unpaid' => Order::where('payment_status', '!=', 'paid')->count(),
            ];

            return response()->json($stats, 200);
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400,
            ];
            return response()->json($data, 400);
        }
    }



    public function destroy(Order $order)
    {
        try {
            DB::transaction(function () use ($order) {
                $order->detail()->delete();
                $order->payment()->delete() ?? null;
                $order->delete();
            });

            return back()->with('success', 'Data Berhasil Dihapus!');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400,
            ];
            return response()->json($data, 400);
        }
    }
}
