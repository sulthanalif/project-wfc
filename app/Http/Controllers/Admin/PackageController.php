<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use Illuminate\Http\Request;
// use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StorepackageRequest;
use App\Http\Requests\UpdatepackageRequest;
use App\Models\Catalog;
use App\Models\PackageCatalog;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $packages = Package::paginate(10);

        return view('cms.admin.pakets.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $catalogs = Catalog::all();
        return view('cms.admin.pakets.create', compact('catalogs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:225', 'string'],
            'description' => ['required', 'max:500', 'string'],
            'catalog_id' => ['nullable', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }
        // return dd($validator);
        try {
            DB::transaction(function () use ($request, &$package) {
                $imageName = 'package_'.time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('public/images/package', $imageName);

                $package = Package::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'image' => $imageName
                ]);

                // Jika pengguna memilih katalog
            if ($request->filled('catalog_id')) {
                // Menambahkan data ke dalam PackageCatalog
                PackageCatalog::create([
                    'package_id' => $package->id,
                    'catalog_id' => $request->catalog_id
                ]);
            }
            });
            if ($package) {
                return redirect()->route('package.index')->with('success' ,'Data Berhasil Ditambahkan!');
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
    public function show(Package $package)
    {
        if ($package) {
            return view('cms.admin.pakets.detail', compact('package'));
        } else {
            return back()->with('error', 'Data Tidak Ditemukan!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        $catalogs = Catalog::all();
        if ($package) {
            return view('cms.admin.pakets.edit', compact('package' , 'catalogs'));
        } else {
            return back()->with('error', 'Data Tidak Ditemukan!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:225', 'string'],
            'description' => ['required', 'max:500', 'string'],
            'catalog_id' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }



        try {
            DB::transaction(function () use ($request, $package, &$update) {
                if ($request->hasFile('image')) {
                    // Delete old image
                    if ($package->image && file_exists(storage_path('app/public/images/package/' . $package->image))) {
                      unlink(storage_path('app/public/images/package/' . $package->image));
                    }

                    // Upload new image
                    $imageName = 'package_'.time() . '.' . $request->file('image')->getClientOriginalExtension();
                    $request->file('image')->storeAs('public/images/package/', $imageName);

                    // Update package data
                    $update = $package->update([
                      'name' => $request->name,
                      'description' => $request->description,
                      'image' => $imageName,
                      // Other package data
                    ]);


                  } else {
                    // Update package data without image
                    $update = $package->update($request->except('image'));
                  }

                  if ($request->filled('catalog_id')) {
                    $packageCatalog = PackageCatalog::where('package_id', $package->id)
                    ->first();

                    if ($packageCatalog) {
                        $packageCatalog->update([
                            'catalog_id' => $request->catalog_id
                        ]);
                    } else {
                        PackageCatalog::create([
                            'package_id' => $package->id,
                            'catalog_id' => $request->catalog_id
                        ]);
                    }
                } else {
                    // Hapus relasi PackageCatalog jika catalog_id kosong
                    $package->catalog()->delete();
                }

            });
            if ($update) {
                return redirect()->route('package.index')->with('success' ,'Data Berhasil Diubah!');
            } else {
                return back()->with('error', 'Data Gagal Diubah!');
            }
        } catch (\Throwable $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return dd($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Package $package)
    {
        if ($package->image && file_exists(storage_path('app/public/images/package/' . $package->image))) {
            unlink(storage_path('app/public/images/package/' . $package->image));
        }

        $packageCatalog = PackageCatalog::where('package_id', $package->id)
                    ->first();

        if ($packageCatalog){
            $package->catalog()->delete();
        }

        $delete = $package->delete();



        if ($delete) {
            return redirect()->route('package.index', ['page' => $request->page])->with('success', 'Data Berhasil Dihapus');
        } else {
            return back()->with('error', 'Data Gagal Dihapus!');
        }
    }
}
