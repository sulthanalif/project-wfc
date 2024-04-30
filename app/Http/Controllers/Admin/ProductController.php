<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use App\Models\ProductPackage;
use App\Models\ProductSupplier;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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
        $products = Product::paginate(10);

        return view('cms.admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $packages = Package::all();
        return view('cms.admin.products.create', compact('suppliers', 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:225', 'string'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'numeric'],
            'days' => [ 'string'],
            'description' => ['required', 'max:500', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'supplier_id' => ['nullable', 'string'],
            'package_id' => ['nullable', 'string'],
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
                    'stock' => $request->stock,
                    'days' => $request->days,
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
                return redirect()->route('product.index')->with('success' ,'Data Berhasil Ditambahkan!');
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
            return view('cms.admin.products.detail', compact('product'));
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
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:225', 'string'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'numeric'],
            'days' => [ 'string'],
            'description' => ['required', 'max:500', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'supplier_id' => ['nullable', 'string'],
            'package_id' => ['nullable', 'string'],
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
                        'stock' => $request->stock,
                        'days' => $request->days
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
                        'days' => $request->days
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
            });
            if ($update) {
                return redirect()->route('product.index')->with('success' ,'Data Berhasil Diupbah!');
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
        // Delete old image
        if ($product->image && file_exists(storage_path('app/public/images/product/' . $product->image))) {
            unlink(storage_path('app/public/images/product/' . $product->image));
        }

        $productSupplier = ProductSupplier::where('product_id', $product->id)
                    ->first();

        $productPackage = ProductPackage::where('product_id', $product->id)
                    ->first();

        if ($productSupplier){
            $product->supplier()->delete();
        }

        if ($productPackage) {
            $product->package()->delete();
        }

        $product->detail()->delete();
        $delete =  $product->delete();

        if ($delete) {
            return redirect()->route('product.index', ['page' => $request->page])->with('success', 'Data Berhasil Dihapus');
        } else {
            return back()->with('error', 'Data Gagal Dihapus!');
        }
    }
}
