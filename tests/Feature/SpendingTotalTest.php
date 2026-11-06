<?php

namespace Tests\Feature;

use App\Models\Spending;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SpendingTotalTest extends TestCase
{
    use DatabaseTransactions;

    public function test_spending_total_is_sum_of_row_totals(): void
    {
        // Pengadaan menyimpan amount = harga x qty; mengalikan qty lagi pernah membuat total ~147x lipat.
        $this->assertSame(0, Spending::where('total_amount', 0)->where('amount', '!=', 0)->count());

        $admin = User::role('super_admin')->firstOrFail();
        $response = $this->actingAs($admin)->get('/finance/spending?period=all');

        $this->assertEquals((float) Spending::sum('total_amount'), (float) $response->viewData('totalSpending'));
    }
}
