<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\GenerateRandomString;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\ProductReturnDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $returns = ProductReturn::with(['user', 'productReturnDetail', 'productReturnDetail.order', 'productReturnDetail.product', 'productReturnDetail.subProduct'])
            ->orderBy('date_in', 'desc')
            ->get();

        return view('cms.return.index', compact('returns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $returns = ProductReturn::all()->count();
        $returnNumber = GenerateRandomString::make(8) . now()->format('dmY') . '-' . ($returns + 1);
        $agents = collect();
        if (!$user->hasRole('agent')) {
            $agents = User::whereHas('roles', function ($query) {
                $query->where('name', 'agent');
            })
                ->where('active', 1)
                ->where('email_verified_at', '!=', null)
                ->where('email_verified_at', '!=', '')
                ->get();
        }

        $orders = Order::with('detail')
            ->where('status', '!=', 'pending')
            ->where('status', '!=', 'reject')
            ->where('status', '!=', 'canceled')
            // ->where('delivery_status', 'success')
            ->get();

        $products = Product::with(['detail', 'subProduct'])->get();

        return view('cms.return.partial.create', compact(['agents', 'returnNumber', 'orders', 'products']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $test = [];

        // dd($request->all());

        $products = json_decode($request->input('products'), true);

        // Perbaiki data request untuk validasi
        $request->merge(['products' => $products]);

        $validator = Validator::make($request->all(), [
            'return_date' => 'required|date',
            'note' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.item_product' => 'required|string',
            'products.*.item_sub_product' => 'required|string',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.item_note' => 'required|string',
            'products.*.proof_image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $products) {
                $return = new ProductReturn();
                $return->return_number = $request->input('return_number');
                $return->user_id = $request->input('agent_id') ?? auth()->user()->id;
                $return->date_in = $request->input('return_date');
                $return->notes = $request->input('note');
                $return->save();

                if (is_array($products)) {
                    foreach ($products as $productData) {
                        $proofImagePath = null;

                        if (!empty($productData['proof_image'])) {
                            $proofImagePath = $this->saveProofImage($productData['proof_image']);
                        }

                        ProductReturnDetail::create([
                            'product_return_id' => $return->id,
                            'order_id' => $request->input('order_id_item'),
                            'product_id' => $productData['item_product'],
                            'sub_product_id' => $productData['item_sub_product'],
                            'status_product' => $productData['item_note'] ?? null,
                            'qty' => $productData['quantity'],
                            'proof_image' => $proofImagePath,
                        ]);
                    }
                }
            });

            return redirect()->route('return.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    private function saveProofImage(string $imageData): ?string
    {
        if (empty($imageData) || !str_contains($imageData, 'data:image')) {
            return null;
        }

        if (!preg_match('/^data:image\/(?<type>jpeg|jpg|png|gif);base64,(?<data>.+)$/i', $imageData, $matches)) {
            return null;
        }

        $directory = storage_path('app/public/images/return');
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $extension = strtolower($matches['type']);
        $randomString = GenerateRandomString::make(8);
        $fileName = 'return_' . $randomString . '.' . $extension;
        $filePath = $directory . DIRECTORY_SEPARATOR . $fileName;
        $decoded = base64_decode($matches['data'], true);

        if ($decoded === false) {
            return null;
        }

        file_put_contents($filePath, $decoded);

        return $fileName;
    }

    private function saveUploadedProofImage($file): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $directory = storage_path('app/public/images/return');
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'png');
        $randomString = GenerateRandomString::make(8);
        $fileName = 'return_' . $randomString . '.' . $extension;
        $file->move($directory, $fileName);

        return $fileName;
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductReturn $return)
    {
        $detail = ProductReturnDetail::with(['order', 'product', 'subProduct'])
            ->where('product_return_id', $return->id)
            ->get();

        $order = Order::with('detail')
            ->where('id', $detail->first()->order_id)
            ->first();

        $products = Product::with(['detail', 'subProduct'])->get();

        return view('cms.return.partial.detail', compact(['return', 'detail', 'order', 'products']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductReturn $return)
    {
        try {
            DB::transaction(function () use ($return) {
                // Hapus detail pengembalian terkait
                $returnDetails = ProductReturnDetail::where('product_return_id', $return->id)->get();

                foreach ($returnDetails as $item) {
                    if ($item->proof_image) {
                        $proofImagePath = storage_path('app/public/images/return/' . $item->proof_image);
                        if (file_exists($proofImagePath)) {
                            unlink($proofImagePath);
                        }
                    }
                }

                ProductReturnDetail::where('product_return_id', $return->id)->delete();

                // Hapus pengembalian
                $return->delete();
            });

            return redirect()->route('return.index')->with('success', 'Data pengembalian berhasil dihapus');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function changeStatus(Request $request, ProductReturn $return)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processed,finished,rejected,canceled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $return) {
                $return->status = $request->input('status');
                $return->save();

                if ($request->input('status') === 'finished') {
                    $return->date_out = now();
                    $return->save();
                }
            });

            return redirect()->route('return.index')->with('success', 'Status pengembalian berhasil diubah');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function addItem(Request $request, ProductReturn $return)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'sub_product_id_item' => 'required|exists:sub_products,id',
            'qty' => 'required|numeric|min:1',
            'status_product' => 'required|in:damaged,expired,overstock,other',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $return) {
                $proofImagePath = null;

                if ($request->hasFile('proof_image')) {
                    $proofImagePath = $this->saveUploadedProofImage($request->file('proof_image'));
                }

                ProductReturnDetail::create([
                    'product_return_id' => $return->id,
                    'order_id' => $request->input('order_id'),
                    'product_id' => $request->input('product_id'),
                    'sub_product_id' => $request->input('sub_product_id_item'),
                    'status_product' => $request->input('status_product'),
                    'qty' => $request->input('qty'),
                    'proof_image' => $proofImagePath,
                ]);
            });

            return redirect()->route('return.show', $return->id)->with('success', 'Item pengembalian berhasil ditambahkan');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function editItem(Request $request, ProductReturn $return, ProductReturnDetail $item)
    {
        $validator = Validator::make($request->all(), [
            'qty' => 'required|numeric|min:1',
            'status_product' => 'required|in:damaged,expired,overstock,other',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $return, $item) {
                $item->qty = $request->input('qty');
                $item->status_product = $request->input('status_product');
                $item->save();
            });

            return redirect()->route('return.show', $return->id)->with('success', 'Item pengembalian berhasil diubah');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function destroyItem(ProductReturn $return, ProductReturnDetail $item)
    {
        try {
            DB::transaction(function () use ($return, $item) {
                // Hapus detail pengembalian terkait
                if ($item->proof_image) {
                    $proofImagePath = storage_path('app/public/images/return/' . $item->proof_image);
                    if (file_exists($proofImagePath)) {
                        unlink($proofImagePath);
                    }
                }

                $item->delete();

                // Hapus pengembalian jika tidak ada detail yang tersisa
                if ($return->productReturnDetail()->count() === 0) {
                    $return->delete();
                }
            });

            return redirect()->route('return.show', $return->id)->with('success', 'Item pengembalian berhasil dihapus');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }
}
