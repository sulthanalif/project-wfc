<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class TableComponentsTest extends TestCase
{
    public function test_table_empty_component_renders_message(): void
    {
        $rendered = $this->blade('<x-table.empty colspan="5" message="Tidak ada data produk" />');
        $this->assertStringContainsString('Tidak ada data produk', $rendered);
        $this->assertStringContainsString('colspan="5"', $rendered);
        $this->assertStringContainsString('data-lucide="inbox"', $rendered);
    }

    public function test_badge_status_component_renders_correct_classes(): void
    {
        $renderedAccepted = $this->blade('<x-badge.status status="accepted" />');
        $this->assertStringContainsString('Diterima', $renderedAccepted);
        $this->assertStringContainsString('bg-success/10', $renderedAccepted);

        $renderedPaid = $this->blade('<x-badge.status status="paid" />');
        $this->assertStringContainsString('Lunas', $renderedPaid);

        $renderedPending = $this->blade('<x-badge.status status="pending" />');
        $this->assertStringContainsString('Pending', $renderedPending);
        $this->assertStringContainsString('bg-warning/10', $renderedPending);
    }

    public function test_table_wrapper_component_renders_table_container(): void
    {
        $rendered = $this->blade('
            <x-table.wrapper>
                <x-slot:header>
                    <tr><th>Nama</th></tr>
                </x-slot:header>
                <tr><td>Produk A</td></tr>
            </x-table.wrapper>
        ');

        $this->assertStringContainsString('table-container', $rendered);
        $this->assertStringContainsString('table-report', $rendered);
        $this->assertStringContainsString('Produk A', $rendered);
    }

    public function test_table_toolbar_and_pagination_components_render(): void
    {
        $paginator = new LengthAwarePaginator(
            collect([['id' => 1], ['id' => 2]]),
            20,
            10,
            1,
            ['path' => '/test']
        );

        $renderedToolbar = $this->blade('<x-table.toolbar :collection="$paginator" />', ['paginator' => $paginator]);
        $this->assertStringContainsString('id="filter"', $renderedToolbar);
        $this->assertStringContainsString('id="records_per_page"', $renderedToolbar);
        $this->assertStringContainsString('Menampilkan 1 hingga 2 dari 20 data', $renderedToolbar);

        $renderedPagination = $this->blade('<x-table.pagination :collection="$paginator" />', ['paginator' => $paginator]);
        $this->assertStringContainsString('pagination', $renderedPagination);
    }

    public function test_app_layout_contains_500ms_debounce_and_loading_overlay(): void
    {
        $appLayout = file_get_contents(resource_path('views/cms/layouts/app.blade.php'));
        $this->assertStringContainsString('setTimeout(triggerServerSearch, 500)', $appLayout);
        $this->assertStringContainsString('showTableLoading', $appLayout);
        $this->assertStringContainsString('table-loading-overlay', $appLayout);
    }
}
