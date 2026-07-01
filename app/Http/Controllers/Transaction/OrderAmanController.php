<?php

namespace App\Http\Controllers\Transaction;

use App\Exports\DetailOrderExport;
use App\Exports\ExportDatas;
use App\Helpers\GenerateRandomString;
use App\Helpers\ValidateRole;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Package;
use App\Models\Period;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Excel;

class OrderAmanController extends Controller
{
    public function index(Request $request)
    {
        $perPages = $request->get('perPage') ?? 5;
        $status = $request->get('status') ?? 'all';

        $query = Order::query()
            ->whereHas('detail.product', function ($query) {
                $query->where('is_safe_point', true);
            })
            ->with(['detail.product', 'agent.agentProfile']);

        if (ValidateRole::check('agent')) {
            $query->where('agent_id', Auth::user()->id);
        }

        if ($status != 'all') {
            $query->where('status', $status);
        }

        if ($perPages == 'all') {
            $orders = $query->orderByDesc('created_at')->get();
        } else {
            $perPage = intval($perPages);
            $orders = $query->orderByDesc('created_at')->paginate($perPage);
        }

        if (ValidateRole::check('agent')) {
            return view('cms.transactions.aman.index', compact('orders'));
        }

        if ($request->get('export') == 'true') {
            $datas = $query->orderByDesc('created_at')->get()->map(function ($item) {
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

        return view('cms.transactions.aman.index', compact('orders'));
    }

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

        return view('cms.transactions.full.detail', compact(['order', 'packages', 'agents', 'selects', 'selectProducts']));
    }

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
            return view('cms.transactions.aman.create', [
                'agents' => User::role('agent')->where('active', 1)->get(),
                'orderNumber' => $orderNumber,
                'packages' => $packages
            ]);
        }
    }

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
                return redirect()->route('order.aman.index')->with('success', 'Pesanan Telah Dibuat');
            } else {
                return redirect()->route('order.aman.index')->with('error', 'Pesanan Gagal Dibuat');
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
