<?php

namespace Tests\Feature;

use App\Http\Controllers\Transaction\OrderFullController;
use Illuminate\Http\Request;
use Tests\TestCase;

class OrderRouteTest extends TestCase
{
    public function test_order_full_route_matches_the_specific_full_page_route(): void
    {
        $request = Request::create('/transaction/order/full', 'GET');
        $route = $this->app['router']->getRoutes()->match($request);

        $this->assertSame(OrderFullController::class . '@index', $route->getActionName());
        $this->assertSame('order.full.index', $route->getName());
    }
}
