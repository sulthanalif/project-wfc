<?php

namespace Tests\Feature;

use App\Models\BankOwner;
use App\Models\Loan;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Period;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BugFixesTest extends TestCase
{
    public function test_b1_broken_routes_are_resolved(): void
    {
        $this->assertFalse(Route::has('accPayment'));
        $this->assertFalse(Route::has('rejectPayment'));
        $this->assertTrue(Route::has('loan.export'));

        $this->assertFalse(Route::has('distribution.edit'));
        $this->assertFalse(Route::has('procurement.destroy'));
        $this->assertFalse(Route::has('tutorial.create'));
    }

    public function test_b3_update_payment_uses_photo_field(): void
    {
        Storage::fake('public');
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin not found');
        }

        $payment = Payment::first();
        if (!$payment) {
            $this->markTestSkipped('No payment found');
        }

        $file = UploadedFile::fake()->image('proof.jpg');

        $response = $this->actingAs($admin)->put(route('updatePayment', $payment), [
            'upd_method' => 'Transfer',
            'photo' => $file,
        ]);

        $response->assertSessionHasNoErrors();
        $payment->refresh();
        $this->assertNotNull($payment->photo);
        $this->assertTrue(Storage::disk('public')->exists('images/proofs/' . $payment->photo));
    }

    public function test_b5_return_change_status_validation(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin not found');
        }

        $return = ProductReturn::first();
        if (!$return) {
            $this->markTestSkipped('No return found');
        }

        // 'canceled' should fail validation because it's not in enum
        $response = $this->actingAs($admin)->put(route('return.changeStatus', $return), [
            'status' => 'canceled',
        ]);
        $response->assertSessionHasErrors('status');

        // 'processed' should pass
        $response2 = $this->actingAs($admin)->put(route('return.changeStatus', $return), [
            'status' => 'processed',
        ]);
        $response2->assertSessionHasNoErrors();
    }

    public function test_b6_payment_status_marks_paid_on_exact_or_overpayment(): void
    {
        $order = Order::first();
        if (!$order) {
            $this->markTestSkipped('No order found');
        }

        $order->total_price = 100000;
        $order->payment_status = 'pending';
        $order->save();

        // Create or update a payment with accepted status >= total_price
        $payment = $order->payment()->where('status', 'accepted')->first();
        if (!$payment) {
            $payment = new Payment([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'order_id' => $order->id,
                'pay' => 150000,
                'status' => 'accepted',
                'method' => 'Transfer',
                'date' => now(),
            ]);
            $payment->save();
        } else {
            $payment->pay = 150000;
            $payment->save();
        }

        $order->recalculatePaymentStatus();
        $this->assertEquals('paid', $order->fresh()->payment_status);
    }

    public function test_b8_activating_period_deactivates_other_periods(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin not found');
        }

        $p1 = Period::first();
        $p2 = Period::skip(1)->first();

        if (!$p1 || !$p2) {
            $this->markTestSkipped('Need at least two periods');
        }

        // Activate p1
        $this->actingAs($admin)->post(route('activatePeriod'), ['id' => $p1->id]);
        $p1->refresh();
        $this->assertEquals(1, $p1->is_active);

        // Activate p2 -> p1 must become inactive
        $this->actingAs($admin)->post(route('activatePeriod'), ['id' => $p2->id]);
        $p1->refresh();
        $p2->refresh();

        $this->assertEquals(0, $p1->is_active);
        $this->assertEquals(1, $p2->is_active);
    }

    public function test_b9_finance_admin_can_access_tutorial_page(): void
    {
        $financeAdmin = User::role('finance_admin')->first();
        if (!$financeAdmin) {
            $this->markTestSkipped('finance_admin user not found');
        }

        $response = $this->actingAs($financeAdmin)->get(route('tutorial.page'));
        $response->assertStatus(200);
    }

    public function test_b10_bank_owner_deletion_blocked_if_used(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin not found');
        }

        $bankWithPayment = BankOwner::whereHas('payments')->first();
        if (!$bankWithPayment) {
            $this->markTestSkipped('No bank owner with payments found');
        }

        $response = $this->actingAs($admin)->delete(route('bank-owner.destroy', $bankWithPayment));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('bank_owners', ['id' => $bankWithPayment->id]);
    }

    public function test_b11_bulk_convert_to_safe_point_does_not_crash_on_missing_product(): void
    {
        $admin = User::role('super_admin')->first();
        if (!$admin) {
            $this->markTestSkipped('Super admin not found');
        }

        // Test with empty or valid order ID without throwing property on null
        $response = $this->actingAs($admin)->post(route('order.bulkConvertToSafePoint'), [
            'order_ids' => 'non-existent-uuid',
        ]);
        $response->assertSessionHasNoErrors();
    }

    public function test_b12_landing_page_renders_successfully(): void
    {
        $response = $this->get(route('landing-page'));
        $response->assertStatus(200);

        $response2 = $this->get(route('company-profile'));
        $response2->assertStatus(200);
    }
}
