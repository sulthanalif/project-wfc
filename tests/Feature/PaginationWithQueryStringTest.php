<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class PaginationWithQueryStringTest extends TestCase
{
    public function test_pagination_links_include_query_strings(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin user not found');
        }

        $response = $this->actingAs($admin)->get('/master/product?perPage=10&custom=filter123');

        $response->assertStatus(200);

        $viewData = $response->viewData('products');
        if ($viewData instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $url = $viewData->url(2);
            $this->assertStringContainsString('perPage=10', $url);
            $this->assertStringContainsString('custom=filter123', $url);
        }
    }

    public function test_views_contain_page_param_deletion_on_per_page_change(): void
    {
        $views = [
            resource_path('views/cms/transactions/index.blade.php'),
            resource_path('views/cms/admin/products/index.blade.php'),
            resource_path('views/cms/sub-agent/index.blade.php'),
            resource_path('views/cms/return/index.blade.php'),
        ];

        foreach ($views as $viewPath) {
            $content = file_get_contents($viewPath);
            $this->assertStringContainsString("urlParams.delete('page');", $content, "Missing urlParams.delete('page') in {$viewPath}");
        }
    }
}
