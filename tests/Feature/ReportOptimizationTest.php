<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class ReportOptimizationTest extends TestCase
{
    protected function getAdmin()
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin user not found');
        }
        return $admin;
    }

    public function test_total_deposit_report_is_paginated_and_preserves_query(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get(route('totalDeposit', ['perPage' => 5]));
        $response->assertStatus(200);

        $datas = $response->viewData('datas');
        $this->assertInstanceOf(LengthAwarePaginator::class, $datas);
        $this->assertEquals(5, $datas->perPage());
    }

    public function test_product_detail_report_is_paginated(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get(route('rproductDetail', ['perPage' => 10]));
        $response->assertStatus(200);

        $datas = $response->viewData('datas');
        $this->assertInstanceOf(LengthAwarePaginator::class, $datas);
    }

    public function test_agent_order_report_is_paginated(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get(route('ragentOrder', ['perPage' => 10]));
        $response->assertStatus(200);

        $datas = $response->viewData('datas');
        $this->assertInstanceOf(LengthAwarePaginator::class, $datas);
    }

    public function test_agent_reward_report_is_paginated(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get(route('ragentReward', ['perPage' => 10]));
        $response->assertStatus(200);

        $datas = $response->viewData('datas');
        $this->assertInstanceOf(LengthAwarePaginator::class, $datas);
    }

    public function test_instalment_report_is_paginated(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get(route('instalment', ['perPage' => 10]));
        $response->assertStatus(200);

        $payments = $response->viewData('payments');
        $this->assertInstanceOf(LengthAwarePaginator::class, $payments);
    }

    public function test_requirement_report_is_paginated(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get(route('requirement', ['perPage' => 10]));
        $response->assertStatus(200);

        $datasubs = $response->viewData('datasubs');
        $this->assertInstanceOf(LengthAwarePaginator::class, $datasubs);
    }

    public function test_stock_sub_product_report_is_paginated(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get(route('stockSubProduct', ['perPage' => 10]));
        $response->assertStatus(200);

        $products = $response->viewData('products');
        $this->assertInstanceOf(LengthAwarePaginator::class, $products);
    }

    public function test_agent_product_detail_is_paginated(): void
    {
        $admin = $this->getAdmin();
        $product = Product::first();
        if (!$product) {
            $this->markTestSkipped('No product available');
        }

        $response = $this->actingAs($admin)->get(route('ragentProductDetail', $product->id));
        $response->assertStatus(200);

        $orders = $response->viewData('orders');
        $this->assertInstanceOf(LengthAwarePaginator::class, $orders);
    }
}
