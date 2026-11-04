<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TableOptimizationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pagination_numbering_on_page_two_continues_sequence(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin user not found');
        }

        // Request page 2 with perPage = 1
        $response = $this->actingAs($admin)->get('/master/product?perPage=1&page=2');
        $response->assertStatus(200);

        // Page 2 item 1 should be numbered 2, not 1
        $products = $response->viewData('products');
        if ($products && $products->total() >= 2) {
            $this->assertEquals(2, $products->firstItem());
            $response->assertSee('<p class="font-medium whitespace-nowrap text-center">2</p>', false);
        }
    }

    public function test_server_side_search_filters_orders(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin user not found');
        }

        $order = Order::whereHas('detail.product.package.package.period', function ($query) {
            $query->where('is_active', 1);
        })->first();

        if (!$order) {
            $order = Order::first();
            if (!$order) {
                $this->markTestSkipped('No order found');
            }
            // If order is from inactive period, test archive search
            $response = $this->actingAs($admin)->get('/transaction/order/archive?search=' . urlencode($order->order_number));
            $response->assertStatus(200);
            $orders = $response->viewData('orders');
            $this->assertNotEmpty($orders);
            $this->assertEquals($order->order_number, $orders->first()->order_number);
            return;
        }

        // Search for this specific order number
        $response = $this->actingAs($admin)->get('/transaction/order?search=' . urlencode($order->order_number));
        $response->assertStatus(200);

        $orders = $response->viewData('orders');
        $this->assertNotEmpty($orders);
        $this->assertEquals($order->order_number, $orders->first()->order_number);

        // Search for non-existent keyword returns empty
        $response2 = $this->actingAs($admin)->get('/transaction/order?search=NONEXISTENT_KEYWORD_XYZ123');
        $response2->assertStatus(200);
        $orders2 = $response2->viewData('orders');
        $this->assertCount(0, $orders2);
    }

    public function test_server_side_search_filters_users(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin user not found');
        }

        $response = $this->actingAs($admin)->get('/master/user?search=' . urlencode($admin->email));
        $response->assertStatus(200);

        $users = $response->viewData('users');
        $this->assertNotEmpty($users);
        $this->assertTrue($users->contains('id', $admin->id));
    }

    public function test_order_full_archive_handles_orphaned_orders_gracefully(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin user not found');
        }

        $response = $this->actingAs($admin)->get('/transaction/order/full/archive');
        $response->assertStatus(200);
    }
}
