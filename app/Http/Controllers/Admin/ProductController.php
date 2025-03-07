<?php

namespace App\Http\Controllers\Admin;

use App\Models\Period;
use App\Models\Package;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\SubProduct;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use App\Models\ProductPackage;
use App\Models\ProductSupplier;
use App\Models\ProductSubProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPages = $request->get('perPage') ?? 5;

        if ($perPages == 'all') {
            $products = Product::whereHas('package.package.period', function ($query) {
                $query->where('is_active', 1);
            })->latest()->get();
        } else {
            $perPage = intval($perPages);
            $products = Product::whereHas('package.package.period', function ($query) {
                $query->where('is_active', 1);
            })->latest()->paginate($perPage);
        }


        return view('cms.admin.products.index', compact('products'));
    }

    public function archive()
    {
        $products = Product::whereHas('package.package.period', function ($query) {
            $query->where('is_active', 0);
        })->latest()->paginate(10);
        dd($products);

        return view('cms.admin.products.archive', compact('products'));
    }

    public function export()
    {
        $products = Product::whereHas('package.package.period', function ($query) {
            $query->where('is_active', 1);
        })->get();
        // return response()->json($products);
        return Excel::download(new ProductExport($products), 'Product_Export_' . now() . '.xlsx');
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            // Storage::disk('public')->put('doc/package/' . $fileName, $request->file('file')->getContent());

            Excel::import(new ProductImport, $file);

            return redirect()->route('product.index')->with('success', 'Data Berhasil Diimport');
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $suppliers = Supplier::all();
        // $packages = Package::all();
        $packages = Package::join('periods', 'packages.period_id', '=', 'periods.id')
            ->where('periods.is_active', 1)
            ->select('packages.*')
            ->get();

        return view('cms.admin.products.create', compact('packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:225', 'string'],
            'price' => ['required', 'numeric'],
            'unit' => ['required', 'string'],
            'days' => ['string'],
            'description' => ['required', 'max:500', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'supplier_id' => ['nullable', 'string'],
            'package_id' => ['nullable', 'string'],
            'is_safe_point' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, &$product) {
                $imageName = 'product_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                Storage::disk('public')->put('images/product/' . $imageName, $request->file('image')->getContent());

                $product = Product::create([
                    'name' => $request->name,
                    'price' => $request->price,
                    'unit' => $request->unit,
                    'days' => $request->days,
                    'total_price' => $request->price * $request->days,
                    'is_safe_point' => $request->is_safe_point,
                ]);

                ProductDetail::create([
                    'product_id' => $product->id,
                    'description' => $request->description,
                    'image' => $imageName
                ]);

                // Jika pengguna memilih katalog
                if ($request->filled('supplier_id')) {
                    // Menambahkan data ke dalam PackageCatalog
                    ProductSupplier::create([
                        'product_id' => $product->id,
                        'supplier_id' => $request->supplier_id
                    ]);
                }

                // Jika pengguna memilih paket
                if ($request->filled('package_id')) {
                    // Menambahkan data ke dalam ProductPackage
                    ProductPackage::create([
                        'product_id' => $product->id,
                        'package_id' => $request->package_id
                    ]);
                }
            });
            if ($product) {
                return redirect()->route('product.index')->with('success', 'Data Berhasil Ditambahkan!');
            } else {
                return back()->with('error', 'Data Gagal Ditambahkan!');
            }
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
            // return $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if ($product) {
            $subProducts = $product->subProduct()->get();
            $itemSubProduct = SubProduct::get();
            return view('cms.admin.products.detail', compact('product', 'subProducts', 'itemSubProduct'));
        } else {
            return back()->with('error', 'Data Tidak Ditemukan!');
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        if ($product) {
            $suppliers = Supplier::all();
            $packages = Package::all();
            return view('cms.admin.products.edit', compact('product', 'suppliers', 'packages'));
        } else {
            return back()->with('error', 'Data Tidak Ditemukan!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:225', 'string'],
            'price' => ['required', 'numeric'],
            'unit' => ['required', 'string'],
            'days' => ['string'],
            'description' => ['required', 'max:500', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'supplier_id' => ['nullable', 'string'],
            'package_id' => ['nullable', 'string'],
            'is_safe_point' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $product, &$update) {
                if ($request->hasFile('image')) {
                    // Delete old image
                    if ($product->image && file_exists(storage_path('app/public/images/product/' . $product->image))) {
                        unlink(storage_path('app/public/images/product/' . $product->image));
                    }

                    $imageName = 'product_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                    Storage::disk('public')->put('images/product/' . $imageName, $request->file('image')->getContent());

                    $update = $product->update([
                        'name' => $request->name,
                        'price' => $request->price,
                        'unit' => $request->unit,
                        'days' => $request->days,
                        'total_price' => $request->price * $request->days,
                        'is_safe_point' => $request->is_safe_point,
                    ]);

                    $product->detail()->update([
                        'description' => $request->description,
                        'image' => $imageName
                    ]);
                } else {
                    $update = $product->update([
                        'name' => $request->name,
                        'price' => $request->price,
                        'stock' => $request->stock,
                        'days' => $request->days,
                        'total_price' => $request->price * $request->days,
                        'is_safe_point' => $request->is_safe_point,
                    ]);

                    $product->detail()->update([
                        'description' => $request->description,
                    ]);
                }

                //supplier
                if ($request->filled('supplier_id')) {
                    $productSupplier = ProductSupplier::where('product_id', $product->id)
                        ->first();

                    if ($productSupplier) {
                        $productSupplier->update([
                            'supplier_id' => $request->supplier_id
                        ]);
                    } else {
                        ProductSupplier::create([
                            'product_id' => $product->id,
                            'supplier_id' => $request->supplier_id
                        ]);
                    }
                } else {
                    // Hapus relasi
                    $product->supplier()->delete();
                }

                //package
                if ($request->filled('package_id')) {
                    $productPackage = ProductPackage::where('product_id', $product->id)
                        ->first();

                    if ($productPackage) {
                        $productPackage->update([
                            'package_id' => $request->package_id
                        ]);
                    } else {
                        ProductPackage::create([
                            'product_id' => $product->id,
                            'package_id' => $request->package_id
                        ]);
                    }
                } else {
                    // Hapus relasi
                    $product->package()->delete();
                }

                $orders = $product->order()->get();

                foreach ($orders as $orderDetail) {
                    $orderDetail->sub_price = $orderDetail->product->total_price * $orderDetail->qty;
                    $orderDetail->save();

                    $order = $orderDetail->order()->first();

                    $order->total_price = $order->detail()->sum('sub_price');
                    $order->save();
                }
            });
            if ($update) {
                return redirect()->route('product.index')->with('success', 'Data Berhasil Diupbah!');
            } else {
                return back()->with('error', 'Data Gagal Diupbah!');
            }
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));

            // return $th->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        $productInOrder = OrderDetail::where('product_id', $product->id)->exists();

        if ($productInOrder) {
            return back()->with('error', 'Produk tidak bisa dihapus karena terdaftar dalam pesanan.');
        }

        try {
            DB::beginTransaction();

            // Hapus gambar lama jika ada
            if (!empty($product->image)) {
                $imagePath = storage_path('app/public/images/product/' . $product->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Hapus relasi produk dengan supplier dan paket jika ada
            if ($product->supplier()->exists()) {
                $product->supplier()->delete();
            }

            if ($product->package()->exists()) {
                $product->package()->delete();
            }

            // Hapus detail produk sebelum menghapus produk utama
            $product->detail()->delete();

            // Hapus produk
            $delete = $product->delete();

            DB::commit();

            if ($delete) {
                return redirect()->back()->with('success', 'Data Berhasil Dihapus');
            } else {
                return back()->with('error', 'Data Gagal Dihapus!');
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan saat menghapus produk: ' . $th->getMessage());
        }
    }

    public function addSubProduct(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return view('cms.error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $product) {
                $relasi = new ProductSubProduct([
                    'product_id' => $product->id,
                    'sub_product_id' => $request->sub_product_id,
                    'amount' => $request->amount
                ]);
                $relasi->save();
            });
            return redirect()->route('product.show', $product)->with('success', 'Data Berhasil Ditambahkan');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function destroySub(Product $product, ProductSubProduct $productSubProduct)
    {
        try {
            $productSubProduct->delete();

            return redirect()->route('product.show', $product)->with('success', 'Data Berhasil Dihapus!');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }
}
