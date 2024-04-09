<?php

namespace App\Http\Controllers\Admin;

use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
// use App\Http\Requests\StoreCatalogRequest;
// use App\Http\Requests\UpdateCatalogRequest;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $catalog = Catalog::paginate(10);

        return view('admin.catalog.index', compact('catalog'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('cms.admin.catalogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:225', 'string'],
            'description' => ['required', 'max:500', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, &$catalog) {
                $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('public/images', $imageName);

                $catalog = Catalog::create([
                    'name' => $request->name,
                    'desctription' => $request->description,
                    'image' => $imageName
                ]);
            });
            if ($catalog) {
                return redirect()->route('catalog.index')->with('success' ,'Data Berhasil Ditambahkan!');
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
    public function show(Request $request, Catalog $catalog)
    {
        if ($catalog) {
            return view('cms.admin.catalogs.detail', compact('$catalog'));
        } else {
            return back()->with('error', 'Data Tidak Ditemukan!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Catalog $catalog)
    {
        if ($catalog) {
            return view('cms.admin.catalogs.edit', compact('$catalog'));
        } else {
            return back()->with('error', 'Data Tidak Ditemukan!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Catalog $catalog)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:225', 'string'],
            'description' => ['required', 'max:500', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }



        try {
            DB::transaction(function () use ($request, $catalog, &$update) {
                if ($request->hasFile('image')) {
                    // Delete old image
                    if ($catalog->image && file_exists(storage_path('app/public/images/' . $catalog->image))) {
                      unlink(storage_path('app/public/images/' . $catalog->image));
                    }

                    // Upload new image
                    $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
                    $request->file('image')->storeAs('public/images', $imageName);

                    // Update catalog data
                    $update = $catalog->update([
                      'name' => $request->name,
                      'description' => $request->description,
                      'image' => $imageName,
                      // Other catalog data
                    ]);
                  } else {
                    // Update catalog data without image
                    $update = $catalog->update($request->except('image'));
                  }

            });
            if ($update) {
                return redirect()->route('catalog.index')->with('success' ,'Data Berhasil Diubah!');
            } else {
                return back()->with('error', 'Data Gagal Diubah!');
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
    public function destroy(Request $request, Catalog $catalog)
    {
        if ($catalog->image && file_exists(storage_path('app/public/images/' . $catalog->image))) {
            unlink(storage_path('app/public/images/' . $catalog->image));
        }

        $delete = $catalog->delete();

        if($delete) {
            return redirect()->route('catalog.index', ['page' => $request->page])->with('success', 'Data Berhasil Dihapus');
        } else {
            return back()->with('error', 'Data Gagal Dihapus!');
        }
    }
}
