<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ComprehensiveAuditTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * List of all application routes updated in the optimalisasi branch.
     */
    protected array $routesToAudit = [
        // Group 1: Transactions & Relations
        '/transaction/order',
        '/transaction/order/archive',
        '/transaction/order/full',
        '/transaction/order/full/archive',
        '/transaction/order/titik-aman',
        '/transaction/order/titik-aman/archive',
        '/transaction/return',
        '/master/sub-agent',
        
        // Group 2: Master Data
        '/master/product',
        '/master/product/archive',
        '/master/sub-product',
        '/master/package',
        '/master/package/archive',
        '/master/user',
        '/master/supplier',
        '/master/catalog',
        '/master/bank-owner',

        // Group 3: Finance & Logistics
        '/transaction/payment',
        '/master/commissions',
        '/master/commissions/archive',
        '/distribution',
        '/finance/income',
        '/finance/spending',
        '/finance/spending-type',
        '/finance/loan',
        '/finance/cash-flow',

        // Group 4: Reports & Supporting
        '/master/rewards',
        '/master/tutorial',
        '/report/total-deposit',
        '/report/product-detail',
        '/report/agent-order',
        '/report/agent-reward',
        '/report/instalment',
        '/report/requirement',
        '/report/stock-sub-product',
        '/report/daily',
        '/report/daily?feature=orders',
        '/report/daily?feature=package',
        '/report/daily?feature=instalment',
        '/report/daily?feature=distribution',
    ];

    public function test_all_routes_render_successfully_for_super_admin(): void
    {
        $admin = User::role('super_admin')->first();
        $this->assertNotNull($admin, 'Super admin user must exist');

        foreach ($this->routesToAudit as $uri) {
            $response = $this->actingAs($admin)->get($uri);
            $this->assertLessThan(
                500,
                $response->getStatusCode(),
                "Route $uri returned server error {$response->getStatusCode()}"
            );
            $this->assertEquals(200, $response->getStatusCode(), "Route $uri failed to return 200 OK");
        }
    }

    public function test_all_routes_support_server_side_search_query_without_error(): void
    {
        $admin = User::role('super_admin')->first();
        $this->assertNotNull($admin);

        foreach ($this->routesToAudit as $uri) {
            $searchUri = str_contains($uri, '?') ? "{$uri}&search=test" : "{$uri}?search=test";
            $response = $this->actingAs($admin)->get($searchUri);
            $this->assertEquals(
                200,
                $response->getStatusCode(),
                "Search query on route $searchUri failed with status {$response->getStatusCode()}"
            );
        }
    }

    public function test_all_routes_support_pagination_parameters_without_error(): void
    {
        $admin = User::role('super_admin')->first();
        $this->assertNotNull($admin);

        foreach ($this->routesToAudit as $uri) {
            $paginatedUri = str_contains($uri, '?') ? "{$uri}&perPage=25&page=1" : "{$uri}?perPage=25&page=1";
            $response = $this->actingAs($admin)->get($paginatedUri);
            $this->assertEquals(
                200,
                $response->getStatusCode(),
                "Pagination on route $paginatedUri failed with status {$response->getStatusCode()}"
            );
        }
    }

    public function test_agent_routes_render_cleanly_for_agent_role(): void
    {
        $agent = User::role('agent')->first();
        if (!$agent) {
            $this->markTestSkipped('Agent user not found');
        }

        $agentAllowedRoutes = [
            '/transaction/order',
            '/transaction/order/archive',
            '/transaction/order/full',
            '/transaction/order/full/archive',
            '/transaction/order/titik-aman',
            '/transaction/order/titik-aman/archive',
            '/transaction/return',
            '/transaction/payment-agent',
            '/report/agent-instalment',
            '/tutorial',
        ];

        foreach ($agentAllowedRoutes as $uri) {
            $response = $this->actingAs($agent)->get($uri);
            $this->assertEquals(
                200,
                $response->getStatusCode(),
                "Agent route $uri failed with status {$response->getStatusCode()}"
            );
        }
    }
}
