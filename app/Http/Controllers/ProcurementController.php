<?php

namespace App\Http\Controllers;

use App\Models\Spending;
use App\Models\SubProduct;
use App\Models\OrderDetail;
use App\Models\SpendingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProcurementController extends Controller
{
    public $spendingType;
    public $datas;

    public function __construct()
    {
        // Menggunakan firstOrCreate agar lebih tangguh
        $this->spendingType = SpendingType::firstOrCreate(['name' => 'Pengadaan']);
        $spendingTypeId = $this->spendingType->id;

        // =========================================================================
        // LANGKAH 1: HITUNG TOTAL KEBUTUHAN DARI SEMUA ORDER (SAMA SEPERTI SEBELUMNYA)
        // =========================================================================
        $orderDetails = OrderDetail::whereHas('order', function ($query) {
            $query->where('status', 'accepted');
        })->with('product.subProduct.subProduct')->get();

        $neededSubProducts = [];
        foreach ($orderDetails as $detail) {
            if (!$detail->product || !$detail->product->subProduct) {
                continue;
            }

            foreach ($detail->product->subProduct as $sub) {
                if (!$sub->subProduct) {
                    continue;
                }
                $subProductId = $sub->subProduct->id;
                $calculatedQty = $detail->qty * $sub->amount;

                if (!isset($neededSubProducts[$subProductId])) {
                    $neededSubProducts[$subProductId] = [
                        'id'    => $subProductId,
                        'name'  => $sub->subProduct->name,
                        'unit'  => $sub->subProduct->unit,
                        'needed' => 0,
                    ];
                }
                $neededSubProducts[$subProductId]['needed'] += $calculatedQty;
            }
        }

        // =========================================================================
        // LANGKAH 2: GABUNGKAN DATA & HITUNG SISA BERDASARKAN KOLOM 'information'
        // =========================================================================
        $finalData = [];
        foreach ($neededSubProducts as $subProductId => $data) {
            $subProductName = $data['name'];
            $needed = $data['needed'];

            // Cari total pengadaan untuk item ini berdasarkan namanya di kolom 'information'
            $procured = Spending::where('spending_type_id', $spendingTypeId)
                                ->where('information', 'Pengadaan: ' . $subProductName)
                                ->sum('qty'); // Gunakan sum() untuk total yang akurat

            $remaining = $needed - $procured;

            $finalData[$subProductId] = [
                'id'          => $data['id'],
                'name'        => $data['name'],
                'unit'        => $data['unit'],
                'needed'      => $needed,
                'procurement' => $procured,
                'remaining'   => $procured < $needed ? $remaining : 0,
                'over' =>   $procured > $needed ? $procured - $needed : 0,
            ];
        }

        $this->datas = $finalData;
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


    public function create()
    {
        $filteredDatasubs = array_filter($this->datas, function ($item) {
            return isset($item['remaining']) && $item['remaining'] > 0;
        });

        return view('cms.admin.finance.procurement.create', [
            'datasubs' => $filteredDatasubs,
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'date'      => 'required|date',
            'method'    => 'required|in:tunai,transfer',
            'bank'      => 'required_if:method,transfer|in:BRI,BCA,Mandiri',
            'items'     => 'required|array|min:1',
            'items.*'   => 'required|integer|min:1',
        ], [
            'items.required' => 'Anda harus memilih setidaknya satu item untuk pengadaan.',
            'items.min'      => 'Anda harus memilih setidaknya satu item untuk pengadaan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. Mulai Database Transaction
        DB::beginTransaction();

        try {
            // Ambil atau buat tipe pengeluaran 'Pengadaan'
            $spendingType = SpendingType::firstOrCreate(['name' => 'Pengadaan']);

            // 3. Looping dan simpan setiap item
            foreach ($request->items as $subProductId => $quantity) {
                $subProduct = SubProduct::findOrFail($subProductId);

                // Hitung total untuk item ini
                $totalAmountForItem = $subProduct->price * $quantity;

                // Buat entri spending baru
                Spending::create([
                    'spending_type_id' => $spendingType->id,
                    'date'             => $request->date,
                    'information'      => 'Pengadaan: ' . $subProduct->name,
                    'qty'              => $quantity,
                    'price'            => $subProduct->price,
                    'amount'           => $totalAmountForItem, // DIGANTI dari 'total' menjadi 'amount'
                    'method'           => $request->method,
                    'bank'             => $request->method === 'transfer' ? $request->bank : null,
                    'user_id'          => auth()->id(),
                ]);
            }

            // 4. Commit transaction
            DB::commit();

            return redirect()->route('spending.index')->with('success', 'Data pengadaan berhasil disimpan.');

        } catch (\Exception $e) {
            // 5. Rollback jika terjadi error
            DB::rollBack();
            Log::error('Gagal menyimpan data pengadaan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }
}
