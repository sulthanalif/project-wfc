<?php

namespace App\Http\Controllers\Admin\Landingpage;

use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = Gallery::first();
        return view('cms.admin.landingpage.gallery', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'subTitle' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $gallery) {
                $gallery->update([
                    'title' => $request->title,
                    'subTitle' => $request->subTitle
                ]);
            });
            return redirect()->back()->with('success', 'Gallery updated successfully');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => $e->getCode()
            ];

            return view('cms.error', compact('data'));
        }
    }

    public function addImage(Request $request, Gallery $gallery)
    {
        $validator = Validator::make($request->all(), [
            'image' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'image_thumb' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $gallery) {
                //sava image
                $imageName = 'gallery_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                Storage::disk('public')->put('images/landingpage/' . $imageName, $request->file('image')->getContent());

                $image = new GalleryImage();
                $image->image = $imageName;
                $image->image_thumb = $request->image_thumb;
                $image->gallery_id = $gallery->id;
                $image->save();
            });
            return redirect()->back()->with('success', 'Image added successfully');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => $e->getCode()
            ];

            return view('cms.error', compact('data'));
        }
    }

    public function deleteImage(GalleryImage $image)
    {
        try {
            DB::transaction(function () use ($image) {
                // Delete old image
                if ($image->image && file_exists(storage_path('app/public/images/landingpage/' . $image->image))) {
                    unlink(storage_path('app/public/images/landingpage/' . $image->image));
                }
                $image->delete();
            });
            return redirect()->back()->with('success', 'Image deleted successfully');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => $e->getCode()
            ];

            return view('cms.error', compact('data'));
        }
    }
}
