<?php

namespace App\Http\Controllers\Transaction;

use App\Exports\InvoiceExport;
use App\Helpers\GenerateRandomString;
use App\Helpers\GetProduct;
use App\Helpers\ValidateRole;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderDetail;
use App\Models\Package;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (ValidateRole::check('agent')) {
            $orders = Order::where('agent_id', Auth::user()->id)->orderByDesc('created_at')->paginate(10);

            return view('cms.transactions.index', compact('orders'));
        } else {
            $orders = Order::orderByDesc('created_at')->paginate(10);

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

        $packages = Package::with('product')->get();

        $user = Auth::user();
        $roleUser = $user->roles->first();
        $roleName = $roleUser->name;

        if ($roleName == 'agent') {
            return view('cms.transactions.create', [
                'agents' => User::role('agent')->where('active', 1)->get(),
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
            'total_price' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, &$order) {



                $order = new Order([
                    'agent_id' => $request->agent_id,
                    'order_number' => $request->order_number,
                    'total_price' => $request->total_price,
                    'order_date' => $request->order_date ? $request->order_date : now(),
                    'status' =>  ValidateRole::check('super_admin||admin') ? 'accepted' : 'pending',
                ]);

                $order->save();

                $products = json_decode($request->products, true);

                // dd($products);

                // Membuat OrderDetail untuk setiap produk
                foreach ($products as $product) {
                    // Mendapatkan detail produk berdasarkan productId
                    $productDetail = Product::findOrFail($product['productId']);

                    // Membuat OrderDetail untuk setiap produk
                    OrderDetail::create([
                        'order_id' => $order->id,
                        // 'sub_agent_id' => $request->sub_agent_item,
                        'sub_agent_id' => $product['subAgentId'],
                        // 'name' => $productDetail->name,
                        'product_id' => $product['productId'],
                        // 'price' => $productDetail->price,
                        'sub_price' => $product['subTotal'],
                        'qty' => $product['qty']
                    ]);
                }
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
    public function show(Order $order)
    {
        
        // $product= GetProduct::detail('Product PHL 1');
        // return response()->json($product);
        return view('cms.transactions.detail', compact('order'));
    }



    public function accOrder(Order $order)
    {
        if ($order) {
            $update = $order->update([
                'status' => 'accepted'
            ]);

            if ($update) {
                return redirect()->route('order.index')->with('success', 'Order Berhasil diterima');
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
                return redirect()->route('order.index')->with('success', 'Order Status Berhasil Diubah');
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
}
