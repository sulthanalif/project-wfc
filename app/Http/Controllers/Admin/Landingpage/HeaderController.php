<?php

namespace App\Http\Controllers\Admin\Landingpage;

use App\Models\Header;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HeaderController extends Controller
{
    public function index()
    {
        $header = Header::first();
        return view('cms.admin.landingpage.header', [
            'headers' => $header,
        ]);
    }

    public function update(Request $request, Header $header)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'subTitle' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'buttonTitle' => 'required',
            'buttonUrl' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $header) {
                // Delete old image
                if ($header->image && file_exists(storage_path('app/public/images/landingpage/' . $header->image))) {
                    unlink(storage_path('app/public/images/landingpage/' . $header->image));
                }

                //sava image
                $imageName = 'header_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                Storage::disk('public')->put('images/landingpage/' . $imageName, $request->file('image')->getContent());

                $header->update([
                    'title' => $request->title,
                    'subTitle' => $request->subTitle,
                    'description' => $request->description,
                    'image' => $imageName,
                    'buttonTitle' => $request->buttonTitle,
                    'buttonUrl' => $request->buttonUrl,
                ]);
            });
            return redirect()->back()->with('success', 'Header updated successfully');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => $e->getCode()
            ];

            return view('cms.error', compact('data'));
        }
    }
}
