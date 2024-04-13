<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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
        return view('cms.admin.products.create');
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
            'description' => ['required', 'max:500', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, &$product) {
                $imageName = 'product_'.time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('public/images/product', $imageName);

                $product = Product::create([
                    'name' => $request->name,
                    'price' => $request->price,
                    'stock' => $request->stock,
                    'description' => $request->description,
                    'image' => $imageName
                ]);
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
            return view('cms.admin.products.edit', compact('product'));
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
            'description' => ['required', 'max:500', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
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

                    $imageName = 'product_'.time() . '.' . $request->file('image')->getClientOriginalExtension();
                    $request->file('image')->storeAs('public/images/product', $imageName);

                    $update = $product->update([
                        'name' => $request->name,
                        'price' => $request->price,
                        'stock' => $request->stock,
                        'description' => $request->description,
                        'image' => $imageName
                    ]);
                } else {
                    $update = $product->update($request->except('image'));
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

        $delete = $product->delete();

        if ($delete) {
            return redirect()->route('product.index', ['page' => $request->page])->with('success', 'Data Berhasil Dihapus');
        } else {
            return back()->with('error', 'Data Gagal Dihapus!');
        }
    }
}
