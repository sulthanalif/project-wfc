<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Distribution;
use Illuminate\Http\Request;
use App\Models\DistributionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Helpers\GenerateRandomString;
use App\Mail\NotificationDistribution;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class DistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPages = $request->get('perPage') ?? 5;

        if ($perPages == 'all') {
            $distributions = Distribution::latest()->get();
        } else {
            $perPage = intval($perPages);
            $distributions = Distribution::latest()->paginate($perPage);
        }

        return view('cms.admin.distributions.index', compact('distributions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $datas = Order::whereIn('status', ['accepted', 'stop'])->wherein('delivery_status', ['pending'])->get();


        $distributionNumber = 'D-' . GenerateRandomString::make(8) . now()->format('dmY');

        return view('cms.admin.distributions.create', compact('datas', 'distributionNumber'));
        // return response()->json($datas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'distribution_number' => ['required', 'string'],
            'date' => ['required', 'date'],
            'driver' => ['required', 'string'],
            'order_id' => ['required']
        ];

        if ($request->method == 'diantar') {
            $rules['police_number'] = ['required', 'string'];
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request) {
                $distribution = new Distribution([
                    'distribution_number' => $request->distribution_number,
                    'date' => $request->date,
                    'police_number' => $request->police_number ?? '-',
                    'driver' => $request->driver,
                    'order_id' => $request->order_id,
                    'status' => $request->method == 'diantar' ? 'on_process' : 'delivered',
                    'is_delivery' => $request->method == 'diantar' ? true : false
                ]);

                $distribution->save();

                $products = json_decode($request->products, true);

                if (is_array($products)) {
                    foreach ($products as $product) {
                        $distributionDetail = new DistributionDetail([
                            'distribution_id' => $distribution->id,
                            'order_detail_id' => $product['productId'],
                            'qty' => $product['qty']
                        ]);
                        $distributionDetail->save();
                    }

                    $order = Order::find($request->order_id);
                    $orderDetails = $order->detail->toArray() ?? [];
                    $qty = array_sum(array_column($orderDetails, 'qty'));

                    $totalQty = 0;
                    $distribution = Distribution::where('order_id', $order->id)->get();
                    foreach ($distribution as $item) {
                        $totalQty += $item->detail->sum('qty');
                    }

                    if ($qty - $totalQty  == 0) {
                        $order->delivery_status = 'success';
                        $order->save();
                    }
                }


                // Mail::to($order->agent->email)->send(new NotificationDistribution($order->distribution));
            });

            return redirect()->route('distribution.index')->with('success', 'Data Berhasil Ditambah');
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
    public function show(Distribution $distribution)
    {
        // $total_money = $distribution->order->get()->sum(function($item) {
        //     return $item->detail->sum(function($detail) {
        //         return $detail->product->sumRupiah() * $detail->qty;
        //     });
        // });

        // $total_money = Product::find('9d2705c6-8f0f-401e-bd52-a3a9ff023392')->sumRupiah();

        // return response()->json(compact('total_money'));
        return view('cms.admin.distributions.detail', compact('distribution'));
        // return response()->json(compact('datas'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Distribution $distribution)
    {
        try {
            DB::transaction(function () use ($distribution) {
                $distribution->detail()->delete();
                $distribution->delete();

                $order = Order::find($distribution->order_id);
                $orderDetails = $order?->detail->toArray() ?? [];
                if($orderDetails) {
                    $qty = array_sum(array_column($orderDetails, 'qty'));

                    $totalQty = 0;
                    $distribution = Distribution::where('order_id', $order->id)->get();
                    foreach ($distribution as $item) {
                        $totalQty += $item->detail->sum('qty');
                    }

                    if ($qty - $totalQty  !== 0) {
                        $order->delivery_status = 'pending';
                        $order->save();
                    }
                }
            });
            return redirect()->route('distribution.index')->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function approve(Distribution $distribution)
    {
        try {
            DB::beginTransaction();

            $distribution->status = 'delivered';
            // $distribution->is_delivery = false;
            $distribution->save();

            DB::commit();

            return redirect()->back()->with('success', 'Data Berhasil Terapprove');

        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }
}
