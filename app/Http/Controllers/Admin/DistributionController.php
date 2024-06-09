<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Distribution;
use Illuminate\Http\Request;
use App\Models\DistributionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Helpers\GenerateRandomString;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class DistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $distributions = Distribution::paginate(10);

        return view('cms.admin.distributions.index', compact('distributions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $datas = Order::whereIn('status', ['accepted', 'stop'])->wherein('delivery_status', ['pending'])->get();
        $distributions = Distribution::get();

        $distributionNumber = 'D-'.GenerateRandomString::make(8) . now()->format('dmY');

        return view('cms.admin.distributions.create', compact('datas', 'distributions', 'distributionNumber'));
        // return response()->json($datas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $products = json_decode($request->all(), true);
        // $datas = [];
        // foreach ($products as $product) {
        //     $datas [] = $product['product_id'];
        // }
        // return response()->json($request->all());
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

                if(is_array($products)) {
                    foreach ($products as $product) {
                        $distributionDetail = new DistributionDetail([
                            'distribution_id' => $distribution->id,
                            'order_detail_id' => $product['productId'],
                            'qty' => $product['qty']
                        ]);
                        $distributionDetail->save();
                    }
                }
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
     * Show the form for editing the specified resource.
     */
    public function edit(Distribution $distribution)
    {
        $orders = Order::where(['status' => 'accepted', 'payment_status' => 'paid'])->get();
        return view('cms.admin.distributions.edit', compact('distribution', 'orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Distribution $distribution)
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
            DB::transaction(function () use ($request, $distribution) {
                $distribution->update([
                    'distribution_number' => $request->distribution_number,
                    'date' => $request->date,
                    'police_number' => $request->police_number,
                    'driver' => $request->driver,
                    'order_id' => $request->order_id
                ]);
            });
            return redirect()->route('distribution.index')->with('success', 'Data Berhasil Diubah');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
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
