<?php

namespace Tests\Feature;

use App\Exports\ExportDatas;
use App\Helpers\TableFilter;
use App\Models\Order;
use App\Models\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class TableFilterTest extends TestCase
{
    use DatabaseTransactions;

    private function defs(): array
    {
        return [
            'period' => ['label' => 'Periode', 'type' => 'period', 'apply' => TableFilter::byOrderPeriod()],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['accepted' => 'Diterima', 'pending' => 'Pending']],
            'order_date' => ['label' => 'Tanggal', 'type' => 'date_range'],
        ];
    }

    public function test_period_defaults_to_active_period(): void
    {
        $active = Period::where('is_active', 1)->value('id');
        $filters = TableFilter::apply(Order::query(), Request::create('/'), $this->defs());

        $this->assertSame((string) $active, $filters['period']['value']);
        $this->assertSame('all', $filters['status']['value']);
    }

    public function test_invalid_values_fall_back_to_default(): void
    {
        $query = Order::query();
        $filters = TableFilter::apply($query, Request::create('/', 'GET', [
            'period' => 'bukan-periode',
            'status' => "x' OR 1=1",
            'order_date_from' => '01-01-2026',
        ]), $this->defs());

        $this->assertSame((string) Period::where('is_active', 1)->value('id'), $filters['period']['value']);
        $this->assertSame('all', $filters['status']['value']);
        $this->assertNull($filters['order_date']['from']);
    }

    public function test_filters_match_plain_queries(): void
    {
        $period = Period::whereHas('package')->firstOrFail();
        $query = Order::query();
        TableFilter::apply($query, Request::create('/', 'GET', [
            'period' => (string) $period->id,
            'status' => 'accepted',
            'order_date_from' => '2025-01-01',
        ]), $this->defs());

        $expected = Order::whereHas('detail.product.package.package', fn ($q) => $q->where('period_id', $period->id))
            ->where('status', 'accepted')
            ->whereDate('order_date', '>=', '2025-01-01')
            ->pluck('id')->sort()->values();

        $this->assertEquals($expected, $query->pluck('id')->sort()->values());
    }

    public function test_order_export_contains_all_filtered_rows_not_only_current_page(): void
    {
        Excel::fake();
        $admin = User::role('super_admin')->firstOrFail();
        $expected = Order::whereIn('orders.id', TableFilter::orderIdsInPeriods(Period::where('is_active', 0)->pluck('id')))
            ->where('status', 'accepted')
            ->count();
        $this->assertGreaterThan(10, $expected, 'butuh lebih dari satu halaman data arsip');

        // Sebelumnya export dijalankan setelah paginate() yang menambah LIMIT 10 ke query yang sama.
        $this->actingAs($admin)->get('/transaction/order/archive?perPage=10&status=accepted&export=true');

        Excel::assertDownloaded('Data Pesanan.xlsx', fn (ExportDatas $export) => $export->collection()->count() === $expected);
    }
}
