<?php

namespace App\Http\Controllers\Admin\Landingpage;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::first();
        return view('cms.admin.landingpage.contact', [
            'contact' => $contact
        ]);
    }

    public function update(Request $request, Contact $contact)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'subTitle' => 'required',
            'address' => 'required',
            'email' => 'required',
            'phoneNumber' => 'required',
            'mapUrl' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $contact) {
                $contact->update($request->all());
            });
            return redirect()->back()->with('success', 'Contact has been updated');
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'status' => 400
            ];
            return view('cms.error', $data);
        }
    }
}
