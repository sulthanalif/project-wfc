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

    public function test_product_detail_only_lists_products_of_selected_period(): void
    {
        $admin = $this->getAdmin();
        $period = \App\Models\Period::orderByDesc('start_date')->first();
        if (!$period) {
            $this->markTestSkipped('Period not found');
        }

        $response = $this->actingAs($admin)->get(route('rproductDetail', ['period' => $period->id, 'perPage' => 50]));
        $response->assertStatus(200);

        $allowed = \Illuminate\Support\Facades\DB::table('product_packages')
            ->join('packages', 'packages.id', '=', 'product_packages.package_id')
            ->where('packages.period_id', $period->id)
            ->pluck('product_packages.product_id')->all();
        foreach ($response->viewData('datas') as $row) {
            $this->assertContains($row['product_id'], $allowed);
        }
    }

    public function test_requirement_only_counts_products_of_selected_period(): void
    {
        $admin = $this->getAdmin();
        $period = \App\Models\Period::orderByDesc('start_date')->first();
        if (!$period) {
            $this->markTestSkipped('Period not found');
        }

        $response = $this->actingAs($admin)->get(route('requirement', ['period' => $period->id]));
        $response->assertStatus(200);

        $products = \Illuminate\Support\Facades\DB::table('product_packages')
            ->join('packages', 'packages.id', '=', 'product_packages.package_id')
            ->where('packages.period_id', $period->id)
            ->select('product_packages.product_id');
        $expected = (int) \Illuminate\Support\Facades\DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('product_sub_products', 'product_sub_products.product_id', '=', 'order_details.product_id')
            ->where('orders.status', 'accepted')
            ->whereIn('order_details.product_id', $products)
            ->sum(\Illuminate\Support\Facades\DB::raw('order_details.qty * product_sub_products.amount'));
        $this->assertSame($expected, (int) $response->viewData('stats')['totalSubProductAll']);
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
