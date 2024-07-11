<?php

namespace App\Http\Controllers\Admin;

use App\Models\Header;
use App\Models\Contact;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MasterLandingPageController extends Controller
{
    public function index()
    {
        $header = Header::first();
        $profile = Profile::first();
        $contact = Contact::first();

        return view('cms.admin.landingpage.index', compact('header', 'profile', 'contact'));
    }

    public function updateHeader(Request $request, Header $header)
    {
        // return response()->json([
        //     'request' => $request->all(),
        //     'header' => $header
        // ]);
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:225', 'string'],
            'sub_title' => ['required', 'max:225', 'string'],
            'description' => ['required', 'max:500', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'button_title' => ['required', 'max:225', 'string'],
            'button_url' => ['required', 'max:225', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $header) {
                if ($request->hasFile('image')) {
                    //delete
                    if ($header->image && file_exists(storage_path('app/public/images/header/' . $header->image))) {
                        unlink(storage_path('app/public/images/header/' . $header->image));
                    }

                    $imageName = 'header_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                    Storage::disk('public')->put('images/header/' . $imageName, $request->file('image')->getContent());

                    $header->update([
                        'title' => $request->title,
                        'sub_title' => $request->sub_title,
                        'description' => $request->description,
                        'image' => $imageName,
                        'button_title' => $request->button_title,
                        'button_url' => $request->button_url,
                    ]);
                } else {
                    $header->update([
                        'title' => $request->title,
                        'sub_title' => $request->sub_title,
                        'description' => $request->description,
                        'button_title' => $request->button_title,
                        'button_url' => $request->button_url,
                    ]);
                }
            });
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        } catch (\Exception $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function updateProfile(Request $request, Profile $profile)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:225', 'string'],
            'text' => ['required', 'max:500', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'button_title' => ['required', 'max:225', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $profile) {
                if ($request->hasFile('image')) {
                    //delete
                    if ($profile->image && file_exists(storage_path('app/public/images/profile/' . $profile->image))) {
                        unlink(storage_path('app/public/images/profile/' . $profile->image));
                    }

                    $imageName = 'profile_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                    Storage::disk('public')->put('images/profile/' . $imageName, $request->file('image')->getContent());

                    $profile->update([
                        'title' => $request->title,
                        'text' => $request->text,
                        'image' => $imageName,
                        'button_title' => $request->button_title,
                    ]);
                } else {
                    $profile->update([
                        'title' => $request->title,
                        'text' => $request->text,
                        'button_title' => $request->button_title,
                    ]);
                }
            });

            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        } catch (\Exception $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

    public function updateContact(Request $request, Contact $contact)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:225', 'string'],
            'sub_title' => ['required', 'max:225', 'string'],
            'address' => ['required', 'max:225', 'string'],
            'email' =>  ['required', 'max:225', 'string'],
            'phone_number' =>  ['required', 'max:225', 'string'],
            'map_link' => ['required', 'max:500', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $contact) {
                $contact->update([
                    'title' => $request->title,
                    'sub_title' => $request->sub_title,
                    'address' => $request->address,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'map_link' => $request->map_link,
                ]);
            });
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        } catch (\Exception $th) {
            $data = [
                'message' => $th->getMessage(),
                'status' => 400
            ];
            return view('cms.error', compact('data'));
        }
    }

}
