<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeleteLastOrderItemTest extends TestCase
{
    use DatabaseTransactions;

    private function orderWithOneItem(): array
    {
        $product = Product::firstOrFail();
        $order = Order::create([
            'agent_id' => (string) Str::uuid(),
            'order_number' => 'TEST-DEL-' . Str::random(6),
            'total_price' => $product->total_price,
            'status' => 'accepted',
            'payment_status' => 'unpaid',
        ]);
        $detail = OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'qty' => 1,
            'sub_price' => $product->total_price,
        ]);

        return [$order, $detail, $product];
    }

    public function test_deleting_last_item_cancels_order_and_zeroes_total(): void
    {
        [$order, $detail, $product] = $this->orderWithOneItem();
        $admin = User::role('super_admin')->firstOrFail();

        $this->actingAs($admin)->post(route('order.editDetail', $detail), ['product_id' => $product->id, 'qty' => 0])
            ->assertSessionHas('success');

        $order->refresh();
        $this->assertSame('canceled', $order->status);
        $this->assertEquals(0, $order->total_price);
        $this->assertSame('unpaid', $order->payment_status);
    }

    public function test_cannot_delete_last_item_when_order_has_payment(): void
    {
        [$order, $detail, $product] = $this->orderWithOneItem();
        $order->payment()->create(['invoice_number' => 'TEST-INV', 'pay' => 1000, 'method' => 'cash', 'status' => 'pending', 'date' => now()]);
        $admin = User::role('super_admin')->firstOrFail();

        $this->actingAs($admin)->post(route('order.editDetail', $detail), ['product_id' => $product->id, 'qty' => 0])
            ->assertSessionHas('error');

        $this->assertSame('accepted', $order->fresh()->status);
        $this->assertTrue(OrderDetail::whereKey($detail->id)->exists());
    }
}
