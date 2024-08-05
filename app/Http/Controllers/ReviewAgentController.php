<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReviewAgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::where('user_id', auth()->user()->id)->paginate(10);

        return view('cms.agen.reviews.index', compact('reviews'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ReviewPage $reviewPage)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            // 'as' => ['required', 'string'],
            'rating' => ['required', 'string'],
            'body' => ['required', 'string'],
            'image' => ['required', 'mimes:png,jpg,jpeg', 'max:2048', 'image'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $reviewPage) {
                //sava image
                $imageName = 'review_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                Storage::disk('public')->put('images/landingpage/' . $imageName, $request->file('image')->getContent());

                $rp = $reviewPage->first();

                $review = new Review();
                $review->review_page_id = $rp->id;
                $review->user_id = auth()->user()->id;
                $review->name = $request->name;
                // $review->as = $request->as;
                $review->rating = $request->rating;
                $review->body = $request->body;
                $review->image = $imageName;
                $review->publish = 0;
                $review->save();
            });
            return redirect()->back()->with('success', 'Review berhasil ditambahkan');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];

            return view('cms.error', compact('data'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, Review $review)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            // 'as' => ['required', 'string'],
            'rating' => ['required', 'string'],
            'body' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $review) {
                if ($request->hasFile('image')) {
                    // Delete old image
                    if ($review->image && file_exists(storage_path('app/public/images/landingpage/' . $review->image))) {
                        unlink(storage_path('app/public/images/landingpage/' . $review->image));
                    }

                    //sava image
                    $imageName = 'review_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                    Storage::disk('public')->put('images/landingpage/' . $imageName, $request->file('image')->getContent());

                    $review->name = $request->name;
                    // $review->as = $request->as;
                    $review->rating = $request->rating;
                    $review->body = $request->body;
                    $review->image = $imageName;
                    $review->save();
                } else {
                    $review->name = $request->name;
                    // $review->as = $request->as;
                    $review->rating = $request->rating;
                    $review->body = $request->body;
                    $review->save();
                }
            });
            return redirect()->back()->with('success', 'Review berhasil diubah');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];

            return view('cms.error', compact('data'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        if ($review->image && file_exists(storage_path('app/public/images/landingpage/' . $review->image))) {
            unlink(storage_path('app/public/images/landingpage/' . $review->image));
        }

        $review->delete();
        return redirect()->back()->with('success', 'Review berhasil dihapus');
    }
}
