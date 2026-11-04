<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EagerLoadingOptimizationTest extends TestCase
{
    public function test_payment_index_eager_loads_and_uses_with_sum(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin user not found');
        }

        $response = $this->actingAs($admin)->get('/transaction/payment');
        $response->assertStatus(200);

        $orders = $response->viewData('orders');
        $this->assertNotNull($orders);

        if ($orders->isNotEmpty()) {
            $firstGroup = $orders->first();
            if ($firstGroup && $firstGroup->isNotEmpty()) {
                $firstOrder = $firstGroup->first();
                $this->assertTrue(isset($firstOrder->paid_total) || array_key_exists('paid_total', $firstOrder->getAttributes()));
                $this->assertTrue($firstOrder->relationLoaded('detail'));
                $this->assertTrue($firstOrder->relationLoaded('agent'));
            }
        }
    }

    public function test_master_product_index_eager_loads_relations(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin user not found');
        }

        $response = $this->actingAs($admin)->get('/master/product');
        $response->assertStatus(200);

        $products = $response->viewData('products');
        $this->assertNotNull($products);

        if ($products->isNotEmpty()) {
            $firstProduct = $products->first();
            $this->assertTrue($firstProduct->relationLoaded('detail'));
            $this->assertTrue($firstProduct->relationLoaded('package'));
        }
    }

    public function test_distribution_index_eager_loads_relations(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin user not found');
        }

        $response = $this->actingAs($admin)->get('/distribution');
        $response->assertStatus(200);

        $distributions = $response->viewData('distributions');
        $this->assertNotNull($distributions);

        if ($distributions->isNotEmpty()) {
            $first = $distributions->first();
            $this->assertTrue($first->relationLoaded('detail'));
        }
    }

    public function test_master_user_index_eager_loads_relations(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin user not found');
        }

        $response = $this->actingAs($admin)->get('/master/user');
        $response->assertStatus(200);

        $users = $response->viewData('users');
        $this->assertNotNull($users);

        if ($users->isNotEmpty()) {
            $firstUser = $users->first();
            $this->assertTrue($firstUser->relationLoaded('roles'));
            $this->assertTrue($firstUser->relationLoaded('agentProfile'));
            $this->assertTrue($firstUser->relationLoaded('adminProfile'));
        }
    }

    public function test_master_package_index_eager_loads_period(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin user not found');
        }

        $response = $this->actingAs($admin)->get(route('package.index'));
        $response->assertStatus(200);

        $packages = $response->viewData('packages');
        $this->assertNotNull($packages);

        if ($packages->isNotEmpty()) {
            $firstPackage = $packages->first();
            $this->assertTrue($firstPackage->relationLoaded('period'));
        }
    }

    public function test_order_index_eager_loads_relations(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin user not found');
        }

        $response = $this->actingAs($admin)->get(route('order.index'));
        $response->assertStatus(200);

        $orders = $response->viewData('orders');
        $this->assertNotNull($orders);

        if ($orders->isNotEmpty()) {
            $firstOrder = $orders->first();
            $this->assertTrue($firstOrder->relationLoaded('detail'));
            $this->assertTrue($firstOrder->relationLoaded('agent'));
            $this->assertTrue($firstOrder->relationLoaded('distributions'));
        }
    }

    public function test_product_return_index_eager_loads_and_counts(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin user not found');
        }

        $response = $this->actingAs($admin)->get(route('return.index'));
        $response->assertStatus(200);

        $returns = $response->viewData('returns');
        $this->assertNotNull($returns);

        if ($returns->isNotEmpty()) {
            $firstReturn = $returns->first();
            $this->assertTrue($firstReturn->relationLoaded('user'));
            $this->assertTrue(isset($firstReturn->product_return_detail_count) || array_key_exists('product_return_detail_count', $firstReturn->getAttributes()));
        }
    }
}
