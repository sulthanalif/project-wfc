<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Distribution;
use Illuminate\Http\Request;
use App\Models\DistributionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Helpers\GenerateRandomString;
use App\Mail\NotificationDistribution;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
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
        $validator = Validator::make($request->all(), [
            'distribution_number' => ['required', 'string'],
            'date' => ['required', 'date'],
            'police_number' => ['required', 'string'],
            'driver' => ['required', 'string'],
            'order_id' => ['required']
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request) {
                $distribution = new Distribution([
                    'distribution_number' => $request->distribution_number,
                    'date' => $request->date,
                    'police_number' => $request->police_number,
                    'driver' => $request->driver,
                    'order_id' => $request->order_id
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


                Mail::to($order->agent->email)->send(new NotificationDistribution($order->distribution));
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
        // $datas = $distribution->order->detail;
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
}
