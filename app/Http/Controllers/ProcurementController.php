<?php

namespace App\Http\Controllers;

use App\Helpers\TableFilter;
use App\Models\Period;
use App\Models\Spending;
use App\Models\SubProduct;
use App\Models\SpendingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProcurementController extends Controller
{
    /**
     * Kebutuhan sub produk dari pesanan accepted vs jumlah yang sudah diadakan (pengeluaran jenis "Pengadaan").
     * Dengan periode: kebutuhan dari pesanan di periode itu, pengadaan dari pengeluaran bertanggal dalam rentang periode.
     */
    private function computeDatas(?Period $period): array
    {
        $spendingTypeId = SpendingType::firstOrCreate(['name' => 'Pengadaan'])->id;

        // Dijumlahkan di database: memuat ribuan detail pesanan sebagai model lalu di-loop di PHP butuh beberapa detik.
        $neededSubProducts = DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('product_sub_products as psp', 'psp.product_id', '=', 'order_details.product_id')
            ->join('sub_products', 'sub_products.id', '=', 'psp.sub_product_id')
            ->where('orders.status', 'accepted')
            ->when($period, fn ($q) => $q->whereIn('order_details.order_id', TableFilter::orderIdsInPeriods([$period->id])))
            ->groupBy('sub_products.id', 'sub_products.name', 'sub_products.unit')
            ->orderBy('sub_products.name')
            ->selectRaw('sub_products.id, sub_products.name, sub_products.unit, SUM(order_details.qty * psp.amount) as needed')
            ->get()
            ->keyBy('id')
            ->map(fn ($row) => (array) $row)
            ->all();

        // Total pengadaan per item dicocokkan lewat kolom 'information' ("Pengadaan: <nama>"), satu query untuk semua item.
        $procuredByInfo = Spending::where('spending_type_id', $spendingTypeId)
            ->when($period, fn ($q) => TableFilter::byPeriodDates('date')($q, $period->id))
            ->groupBy('information')
            ->selectRaw('information, SUM(qty) as total')
            ->pluck('total', 'information')
            // Sama seperti perbandingan kolom di MySQL: tanpa beda huruf besar/kecil dan spasi di akhir.
            ->mapWithKeys(fn ($total, $info) => [mb_strtolower(rtrim($info)) => $total]);

        $finalData = [];
        foreach ($neededSubProducts as $subProductId => $data) {
            $needed = $data['needed'];
            $procured = (float) ($procuredByInfo[mb_strtolower(rtrim('Pengadaan: ' . $data['name']))] ?? 0);

            $finalData[$subProductId] = [
                'id'          => $data['id'],
                'name'        => $data['name'],
                'unit'        => $data['unit'],
                'needed'      => $needed,
                'procurement' => $procured,
                'remaining'   => $procured < $needed ? $needed - $procured : 0,
                'over' =>   $procured > $needed ? $procured - $needed : 0,
            ];
        }

        return $finalData;
    }

    public function index(Request $request)
    {
        $filters = TableFilter::apply(Period::query(), $request, [
            'period' => ['label' => 'Periode', 'type' => 'period', 'apply' => null],
        ]);
        $periodId = $filters['period']['value'];
        $procurements = $this->computeDatas($periodId === 'all' ? null : Period::find($periodId));

        return view('cms.admin.finance.procurement.index', compact('procurements', 'filters'));
    }

    public function show(Spending $spending)
    {
        //
    }


    public function create()
    {
        $active = Period::where('is_active', 1)->first();
        $filteredDatasubs = array_filter($this->computeDatas($active), function ($item) {
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
                    'total_amount'     => $totalAmountForItem,
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
