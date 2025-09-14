<?php

namespace App\Http\Controllers;

use App\Models\Spending;
use App\Models\SubProduct;
use App\Models\OrderDetail;
use App\Models\SpendingType;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
    public $spendingType;
    public $datas;

    public function __construct()
    {
        $this->spendingType = SpendingType::where('name', 'Pengadaan')->first();

        // 1. Ambil semua detail order yang statusnya 'accepted' dalam satu query
        // Ini adalah langkah optimisasi utama untuk menghindari N+1 problem
        $orderDetails = OrderDetail::whereHas('order', function ($query) {
            $query->where('status', 'accepted');
        })->with('product.subProduct.subProduct')->get();

        if ($this->spendingType) {
            $spendingProcurements = Spending::where('spending_type_id', $this->spendingType->id)->get();
        } else {
            $spendingProcurements = collect();
        }

        $datas = [];
        $datasubs = [];

        foreach ($orderDetails as $detail) {
            // Lewati jika detail tidak memiliki produk terkait (data integrity)
            if (!$detail->product) {
                continue;
            }

            $productId = $detail->product_id;
            $qty = $detail->qty;

            // 2. Agregasi data produk utama menggunakan ID produk sebagai key array
            // Ini jauh lebih cepat daripada looping untuk mencari data yang sudah ada
            if (!isset($datas[$productId])) {
                $datas[$productId] = [
                    'product_id'   => $productId,
                    'product_name' => $detail->product->name,
                    'qty'          => 0,
                    'price'        => 0,
                ];
            }
            $datas[$productId]['qty'] += $qty;
            $datas[$productId]['price'] += ($detail->product->price * $detail->product->days) * $qty;

            // 3. Agregasi data sub-produk
            if ($detail->product->subProduct) {
                foreach ($detail->product->subProduct as $sub) {
                    // Lewati jika relasi sub-produk tidak lengkap
                    if (!$sub->subProduct) {
                        continue;
                    }

                    // Get spending data efficiently with a single query
                    // $spending = Spending::where('information', 'like', '%' . $sub->name . '%')
                    //     ->select(DB::raw('COALESCE(SUM(qty), 0) as total_qty'))
                    //     ->first();

                    $subProductId = $sub->subProduct->id;
                    $subProductAmount = $sub->amount;
                    $calculatedQty = $qty * $subProductAmount;

                    if ($spendingProcurements->where('information', 'like', '%' . $sub->name . '%')->count() > 0) {
                        $procurement = $spendingProcurements->where('information', 'like', '%' . $sub->name . '%')->first();
                    } else {
                        $procurement = (object)[
                            'qty' => 0
                        ];
                    }

                    // Gunakan ID sub-produk sebagai key untuk agregasi cepat
                    if (!isset($datasubs[$subProductId])) {
                        $datasubs[$subProductId] = [
                            'id'    => $subProductId,
                            'name'  => $sub->subProduct->name,
                            'unit'  => $sub->subProduct->unit,
                            'qty'   => 0,
                            'price' => 0,
                        ];
                    }
                    $datasubs[$subProductId]['qty'] += $calculatedQty;
                    $datasubs[$subProductId]['price'] += $calculatedQty * $sub->subProduct->price;
                    $datasubs[$subProductId]['procurement'] = $procurement->qty ?? 0;
                    $datasubs[$subProductId]['remaining'] = $datasubs[$subProductId]['qty'] - ($procurement->qty ?? 0);
                }
            }
        }

        $this->datas = $datasubs;
    }

    public function index(Request $request)
    {
        $procurements = $this->datas;


        return view('cms.admin.finance.procurement.index', compact('procurements'));
    }

    public function show(Spending $spending)
    {
        //
    }

    // public function create()
    // {
    //     $subProductIds = OrderDetail::whereHas('order', function ($query) {
    //         $query->where('status', 'accepted');
    //     })
    //     ->whereHas('product.subProduct')
    //     ->with(['product.subProduct:product_id,sub_product_id'])
    //     ->get()
    //     ->pluck('product.subProduct.*.sub_product_id')
    //     ->flatten()
    //     ->unique()
    //     ->values()
    //     ->toArray();

    //     if (empty($subProductIds)) {
    //         return view('cms.admin.finance.procurement.create', ['datasubs' => []]);
    //     }

    //     $datasubs = SubProduct::whereIn('id', $subProductIds)->get();

    //     $finalDatasubs = $datasubs->map(function ($sub) {
    //         return [
    //             'id'    => $sub->id,
    //             'name'  => $sub->name,
    //             'unit'  => $sub->unit,
    //         ];
    //     })->toArray();

    //     return view('cms.admin.finance.procurement.create', [
    //         'datasubs' => $finalDatasubs,
    //     ]);
    // }

    public function create()
    {
        // 1. Ambil semua detail order yang statusnya 'accepted' dalam satu query
        // Ini adalah langkah optimisasi utama untuk menghindari N+1 problem
        $orderDetails = OrderDetail::whereHas('order', function ($query) {
            $query->where('status', 'accepted');
        })->with('product.subProduct.subProduct')->get();

        $datas = [];
        $datasubs = [];

        foreach ($orderDetails as $detail) {
            // Lewati jika detail tidak memiliki produk terkait (data integrity)
            if (!$detail->product) {
                continue;
            }

            $productId = $detail->product_id;
            $qty = $detail->qty;

            // 2. Agregasi data produk utama menggunakan ID produk sebagai key array
            // Ini jauh lebih cepat daripada looping untuk mencari data yang sudah ada
            if (!isset($datas[$productId])) {
                $datas[$productId] = [
                    'product_id'   => $productId,
                    'product_name' => $detail->product->name,
                    'qty'          => 0,
                    'price'        => 0,
                ];
            }
            $datas[$productId]['qty'] += $qty;
            $datas[$productId]['price'] += ($detail->product->price * $detail->product->days) * $qty;

            // 3. Agregasi data sub-produk
            if ($detail->product->subProduct) {
                foreach ($detail->product->subProduct as $sub) {
                    // Lewati jika relasi sub-produk tidak lengkap
                    if (!$sub->subProduct) {
                        continue;
                    }

                    // Get spending data efficiently with a single query
                    // $spending = Spending::where('information', 'like', '%' . $sub->name . '%')
                    //     ->select(DB::raw('COALESCE(SUM(qty), 0) as total_qty'))
                    //     ->first();

                    $subProductId = $sub->subProduct->id;
                    $subProductAmount = $sub->amount;
                    $calculatedQty = $qty * $subProductAmount;

                    // Gunakan ID sub-produk sebagai key untuk agregasi cepat
                    if (!isset($datasubs[$subProductId])) {
                        $datasubs[$subProductId] = [
                            'id'    => $subProductId,
                            'name'  => $sub->subProduct->name,
                            'unit'  => $sub->subProduct->unit,
                            'qty'   => 0,
                            'price' => 0,
                        ];
                    }
                    $datasubs[$subProductId]['qty'] += $calculatedQty;
                    $datasubs[$subProductId]['price'] += $calculatedQty * $sub->subProduct->price;
                }
            }
        }

        // return response()->json($datasubs);

        return view('cms.admin.finance.procurement.create', [
            'datasubs' => $datasubs,
        ]);
    }
}
