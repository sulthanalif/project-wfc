<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use App\Models\User;
use App\Models\Header;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Product;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\DetailProfile;
use App\Models\Review;
use App\Models\ReviewPage;
use App\Models\Reward;

class LandingpageController extends Controller
{
    public function index()
    {
        $products = Product::paginate(5);
        $reviewPage = ReviewPage::first();
        $reviews = Review::where('publish', 1)->get();
        $rewards = Reward::whereHas('period', function ($query) {
            $query->where('is_active', 1);
        })->get();

        $header = Header::first();
        $profile = Profile::first();
        $benefit = Benefit::first();
        $gallery = Gallery::first();
        $contact = Contact::first();

        return view('landing.welcome', compact(
            'products',
            'reviewPage',
            'reviews',
            'rewards',
            'header',
            'profile',
            'benefit',
            'gallery',
            'contact'
        ));
    }

    public function profile()
    {
        $users = User::all();
        $detailProfile = DetailProfile::first();
        $agentUsers = [];

        foreach ($users as $user) {
            if ($user->roles->first()->name == 'agent') {
                $agentUsers[] = $user;
            }
        }

        return view('landing.pages.profile', compact('agentUsers', 'detailProfile'));
    }

    public function catalogs()
    {
        $products = Product::all();

        return view('landing.pages.catalogs', compact('products'));
    }
}
