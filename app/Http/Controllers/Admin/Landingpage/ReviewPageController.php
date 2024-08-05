<?php

namespace App\Http\Controllers\Admin\Landingpage;

use App\Models\Review;
use App\Models\ReviewPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReviewPageController extends Controller
{
    public function index()
    {
        $review = ReviewPage::first();
        return view('cms.admin.landingpage.review', compact('review'));
    }

    public function update(Request $request, ReviewPage $review)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'subTitle' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $review) {
                $review->title = $request->title;
                $review->subTitle = $request->subTitle;
                $review->save();
            });
            return redirect()->back()->with('success', 'Review Page Updated Successfully');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];

            return view('cms.error', compact('data'));
        }
    }

    public function addReview(Request $request, ReviewPage $reviewPage)
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

                $review = new Review();
                $review->review_page_id = $reviewPage->id;
                $review->user_id = auth()->user()->id;
                $review->name = $request->name;
                // $review->as = $request->as;
                $review->rating = $request->rating;
                $review->body = $request->body;
                $review->image = $imageName;
                $review->publish = 0;
                $review->save();
            });
            return redirect()->back()->with('success', 'Review Added Successfully');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];

            return view('cms.error', compact('data'));
        }
    }

    public function updateReview(Request $request, Review $review)
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
            return redirect()->back()->with('success', 'Review Updated Successfully');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];

            return view('cms.error', compact('data'));
        }
    }

    public function deleteReview(Review $review)
    {
         // Delete old image
         if ($review->image && file_exists(storage_path('app/public/images/landingpage/' . $review->image))) {
            unlink(storage_path('app/public/images/landingpage/' . $review->image));
        }

        $review->delete();
        return redirect()->back()->with('success', 'Review Deleted Successfully');
    }

    public function publishReview(Request $request)
    {
        try {
            $reviewPublish = Review::where('publish', 1)->first();
            $newPublish = Review::find($request->id);
            if ($reviewPublish) {
                $reviewPublish->publish = 0;
                $reviewPublish->save();
                $newPublish->publish = 1;
                $newPublish->save();
            } else {
                $newPublish->publish = 1;
                $newPublish->save();
            }
            return back()->with('success', 'Review has been published');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];

            return view('cms.error', compact('data'));
        }
    }
}
