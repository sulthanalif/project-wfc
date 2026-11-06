<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderResubmitTest extends TestCase
{
    use DatabaseTransactions;

    public function test_resubmit_with_existing_order_number_does_not_create_duplicate(): void
    {
        $admin = User::role('super_admin')->firstOrFail();
        $order = Order::firstOrFail();
        $before = Order::count();

        $this->actingAs($admin)
            ->post(route('order.store'), [
                'agent_id' => $order->agent_id,
                'order_number' => $order->order_number,
                'order_date' => now()->toDateString(),
                'total_price' => 1000,
                'products' => json_encode([['productId' => 'x', 'subAgentId' => null, 'qty' => 1, 'subTotal' => 1000]]),
            ])
            ->assertRedirect(route('order.index'))
            ->assertSessionHas('success', 'Pesanan sudah tersimpan');

        $this->assertSame($before, Order::count());
    }

    public function test_database_rejects_duplicate_order_number(): void
    {
        $order = Order::firstOrFail();
        $copy = $order->replicate();

        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);
        $copy->save();
    }

    public function test_success_flash_survives_fetch_redirect(): void
    {
        $admin = User::role('super_admin')->firstOrFail();

        $this->actingAs($admin)
            ->withSession(['success' => 'Pesanan Telah Dibuat', '_flash' => ['old' => ['success'], 'new' => []]])
            ->get(route('order.index'), ['X-Order-Fetch' => '1'])
            ->assertOk();

        $this->assertSame('Pesanan Telah Dibuat', session('success'));
        $this->assertContains('success', session('_flash.old'));
    }
}
