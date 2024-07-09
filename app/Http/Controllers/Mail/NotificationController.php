<?php

namespace App\Http\Controllers\Mail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\NotificationApproveUser;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function sendEmail()
    {
        try {
            Mail::to('sulthanalif45@gmail.com')->send(new NotificationApproveUser);

            return response()->json('success');
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
