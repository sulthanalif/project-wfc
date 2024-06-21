<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::latest()->paginate(10);

        return view('cms.admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        return view('cms.admin.reviews.detail', compact('review'));
    }

    public function create()
    {
        return view('cms.admin.reviews.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'name' => 'required|string',
           'as' => 'nullable|string', 
           'rating' => 'required|integer',
           'body' => 'required|string',
           'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request) {
                if ($request->hasFile('image')) {
                    $imageName = 'review_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                    Storage::disk('public')->put('images/review/' . $imageName, $request->file('image')->getContent());
                }

                $review = new Review([
                    'name' => $request->name,
                    'as' => $request->as,
                    'rating' => $request->rating,
                    'body' => $request->body,
                    'image' => $imageName ?? null,
                ]);
                $review->save();
            });
            return redirect()->route('review.index')->with('success', 'Data Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(Review $review)
    {
        return view('cms.admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $validator = Validator::make($request->all(), [
           'name' => 'required|string',
           'as' => 'nullable|string', 
           'rating' => 'required|integer',
           'body' => 'required|string',
           'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request, $review) {
                if ($request->hasFile('image')) {
                    $imageName = 'review_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                    Storage::disk('public')->put('images/review/' . $imageName, $request->file('image')->getContent());
                }

                $review->update([
                    'name' => $request->name,
                    'as' => $request->as,
                    'rating' => $request->rating,
                    'body' => $request->body,
                    'image' => $imageName ?? $review->image,
                ]);
            });
            return redirect()->route('review.index')->with('success', 'Data Berhasil Diupdate');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Review $review)
    {
        try {
            DB::transaction(function () use ($review) {
                // Delete old image
                if ($review->image && file_exists(storage_path('app/public/images/review/' . $review->image))) {
                    unlink(storage_path('app/public/images/review/' . $review->image));
                }

                $review->delete();
            });
            return redirect()->route('review.index')->with('success', 'Data Berhasil Dihapus');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    
}
