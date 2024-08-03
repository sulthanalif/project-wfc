<?php

namespace App\Http\Controllers\Admin;

use App\Models\SpendingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SpendingTypeController extends Controller
{
    //route: spendingType.index
    public function index()
    {
        $spendingTypes = SpendingType::latest()->paginate(5);
        return view('cms.admin.finance.spendingtype.index', compact('spendingTypes'));
    }

    //route: spendingType.storeOrUpdate
    public function storeAndUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            $oldType = SpendingType::find($request->id);
            DB::transaction(function () use ($request, $oldType) {
                $data = $oldType ?? new SpendingType();
                $data->name = $request->name;
                $data->save();
            });
            return back()->with('success', $oldType ? 'Data Berhasil Diubah!' : 'Data Berhasil Ditambahkan!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    //route: spendingType.destroy, $spendingtype
    public function destroy(SpendingType $spendingType)
    {
        $spendingType->delete();
        return back()->with('success', 'Data Berhasil Dihapus!');
    }
}
