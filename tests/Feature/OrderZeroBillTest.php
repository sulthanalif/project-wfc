<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderZeroBillTest extends TestCase
{
    public function test_recalculate_payment_status_does_not_mark_zero_order_as_paid(): void
    {
        $order = new Order();
        $order->agent_id = (string) \Illuminate\Support\Str::uuid();
        $order->order_number = 'TEST-ORD-01';
        $order->total_price = 0;
        $order->status = 'accepted';
        $order->payment_status = 'unpaid';
        $order->save();

        $order->recalculatePaymentStatus();
        $this->assertEquals('unpaid', $order->fresh()->payment_status);

        $order->delete();
    }
}
