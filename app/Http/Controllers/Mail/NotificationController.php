<?php

namespace App\Http\Controllers\Mail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\NotificationAccOrder;
use App\Mail\NotificationApproveUser;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function sendEmail()
    {

        try {
            $order = Order::where('order_number', 'V2NU8ABS09072024-1')->first();
            Mail::to('sulthanalif45@gmail.com')->send(new NotificationAccOrder($order));

            return response()->json('success');
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
