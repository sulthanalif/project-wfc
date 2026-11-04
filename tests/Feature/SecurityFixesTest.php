<?php

namespace Tests\Feature;

use App\Models\Administration;
use App\Models\AgentProfile;
use App\Models\BankOwner;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Period;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\ProductReturnDetail;
use App\Models\SubAgent;
use App\Models\SubProduct;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SecurityFixesTest extends TestCase
{
    use DatabaseTransactions;

    private function getOrCreateRole(string $name): Role
    {
        return Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
    }

    private function createAgentUser(string $name, string $email): User
    {
        $this->getOrCreateRole('agent');
        $user = User::create([
            'id' => (string) Str::uuid(),
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password'),
            'active' => 1,
            'email_verified_at' => now(),
        ]);
        $user->assignRole('agent');

        AgentProfile::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'name' => $name,
            'phone_number' => '08123456789',
            'address' => 'Test Address',
        ]);

        return $user;
    }

    public function test_s1_agent_cannot_call_status_change_routes_or_methods(): void
    {
        $agent = $this->createAgentUser('Agent S1', 'agent_s1@example.com');
        $order = Order::first();
        $payment = Payment::first();
        $return = ProductReturn::first();

        if (!$order || !$payment || !$return) {
            $this->markTestSkipped('Fixtures missing');
        }

        // POST /transaction/acc/{order}
        $res1 = $this->actingAs($agent)->post(route('order.accOrder', $order));
        $res1->assertStatus(403);

        // POST /transaction/changeOrderStatus/{order}
        $res2 = $this->actingAs($agent)->post(route('order.changeOrderStatus', $order), [
            'status' => 'accepted',
            'description' => 'Malicious status change',
        ]);
        $res2->assertStatus(403);

        // POST /transaction/bulk-convert-to-safe-point
        $res3 = $this->actingAs($agent)->post(route('order.bulkConvertToSafePoint'), [
            'order_ids' => $order->id,
        ]);
        $res3->assertStatus(403);

        // PUT /transaction/changePaymentStatus/{payment}
        $res4 = $this->actingAs($agent)->put(route('changePaymentStatus', $payment), [
            'status' => 'accepted',
        ]);
        $res4->assertStatus(403);

        // PUT /transaction/changePaymentVerify/{payment}
        $res5 = $this->actingAs($agent)->put(route('changePaymentVerify', $payment));
        $res5->assertStatus(403);

        // PUT /transaction/return/{return}/change-status
        $res6 = $this->actingAs($agent)->put(route('return.changeStatus', $return), [
            'status' => 'finished',
        ]);
        $res6->assertStatus(403);
    }

    public function test_s2_ownership_idor_prevented_on_orders_and_details(): void
    {
        $agentA = $this->createAgentUser('Agent A', 'agent_a@example.com');
        $agentB = $this->createAgentUser('Agent B', 'agent_b@example.com');

        $orderB = Order::create([
            'id' => (string) Str::uuid(),
            'agent_id' => $agentB->id,
            'order_number' => 'ORD-TEST-B-' . time(),
            'total_price' => 150000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'delivery_status' => 'pending',
        ]);

        $product = Product::first();
        $subAgentB = SubAgent::create([
            'agent_id' => $agentB->id,
            'name' => 'Sub Agent B',
            'address' => 'Address B',
            'phone_number' => '08123456789',
        ]);

        $detailB = OrderDetail::create([
            'order_id' => $orderB->id,
            'sub_agent_id' => $subAgentB->id,
            'product_id' => $product->id,
            'sub_price' => 150000,
            'qty' => 1,
        ]);

        // Agent A cannot access Agent B's order show
        $this->actingAs($agentA)->get(route('order.show', $orderB))->assertStatus(403);
        $this->actingAs($agentA)->get(route('order.archive.show', $orderB))->assertStatus(403);
        $this->actingAs($agentA)->get(route('order.full.show', $orderB))->assertStatus(403);
        $this->actingAs($agentA)->get(route('order.full.archive.show', $orderB))->assertStatus(403);
        $this->actingAs($agentA)->get(route('order.aman.show', $orderB))->assertStatus(403);
        $this->actingAs($agentA)->get(route('order.aman.archive.show', $orderB))->assertStatus(403);

        // Agent A cannot edit detail of Agent B's order
        $this->actingAs($agentA)->post(route('order.editDetail', $detailB), [
            'product_id' => $product->id,
            'qty' => 2,
        ])->assertStatus(403);

        // Agent A cannot add items to Agent B's order
        $this->actingAs($agentA)->post(route('order.addItems', $orderB), [
            'products' => json_encode([[
                'productId' => $product->id,
                'subAgentId' => $subAgentB->id,
                'qty' => 1,
            ]]),
        ])->assertStatus(403);

        // Agent A cannot view invoice of Agent B's order
        $this->actingAs($agentA)->get(route('getInvoiceOrder', $orderB))->assertStatus(403);
    }

    public function test_s2_ownership_idor_prevented_on_payments(): void
    {
        $agentA = $this->createAgentUser('Agent PA', 'agent_pa@example.com');
        $agentB = $this->createAgentUser('Agent PB', 'agent_pb@example.com');

        $orderB = Order::create([
            'id' => (string) Str::uuid(),
            'agent_id' => $agentB->id,
            'order_number' => 'ORD-PAY-B-' . time(),
            'total_price' => 200000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'delivery_status' => 'pending',
        ]);

        $paymentB = Payment::create([
            'id' => (string) Str::uuid(),
            'order_id' => $orderB->id,
            'invoice_number' => 'INV-TEST-B-' . time(),
            'pay' => 100000,
            'method' => 'Transfer',
            'date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        // Agent A cannot view Agent B's payment detail
        $this->actingAs($agentA)->get(route('payment-agent.detail', ['user' => $agentB, 'order' => $orderB]))->assertStatus(403);

        // Agent A cannot store payment on Agent B's order
        $this->actingAs($agentA)->post(route('storePaymentAgent', $orderB), [
            'pay' => 50000,
            'method' => 'Cash',
            'date' => now()->toDateString(),
        ])->assertStatus(403);

        // Agent A cannot update payment of Agent B
        $this->actingAs($agentA)->put(route('updatePaymentAgent', $paymentB), [
            'upd_method' => 'Cash',
        ])->assertStatus(403);
    }

    public function test_s2_ownership_idor_prevented_on_returns_and_sub_agents(): void
    {
        $agentA = $this->createAgentUser('Agent RA', 'agent_ra@example.com');
        $agentB = $this->createAgentUser('Agent RB', 'agent_rb@example.com');

        $product = Product::first();
        $subProduct = SubProduct::first();

        $returnB = ProductReturn::create([
            'return_number' => 'RET-B-' . time(),
            'user_id' => $agentB->id,
            'date_in' => now()->toDateString(),
            'status' => 'pending',
        ]);

        $orderB = Order::create([
            'id' => (string) Str::uuid(),
            'agent_id' => $agentB->id,
            'order_number' => 'ORD-RET-B-' . time(),
            'total_price' => 50000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'delivery_status' => 'pending',
        ]);

        $itemB = ProductReturnDetail::create([
            'product_return_id' => $returnB->id,
            'order_id' => $orderB->id,
            'product_id' => $product->id,
            'sub_product_id' => $subProduct ? $subProduct->id : $product->id,
            'status_product' => 'damaged',
            'qty' => 1,
        ]);

        // Agent A cannot view, destroy, or add/edit/destroy item on Agent B's return
        $this->actingAs($agentA)->get(route('return.show', $returnB))->assertStatus(403);
        $this->actingAs($agentA)->delete(route('return.destroy', $returnB))->assertStatus(403);
        $this->actingAs($agentA)->post(route('return.item.create', $returnB), [
            'product_id' => $product->id,
            'sub_product_id_item' => $subProduct ? $subProduct->id : $product->id,
            'qty' => 1,
            'status_product' => 'damaged',
        ])->assertStatus(403);
        $this->actingAs($agentA)->put(route('return.item.update', ['return' => $returnB, 'item' => $itemB]), [
            'qty' => 2,
            'status_product' => 'damaged',
        ])->assertStatus(403);
        $this->actingAs($agentA)->delete(route('return.item.destroy', ['return' => $returnB, 'item' => $itemB]))->assertStatus(403);

        // Sub Agent IDOR checks
        $subAgentB = SubAgent::create([
            'agent_id' => $agentB->id,
            'name' => 'Sub Agent RB',
            'address' => 'Address RB',
            'phone_number' => '08123456789',
        ]);

        $this->actingAs($agentA)->get(route('sub-agent.show', $subAgentB))->assertStatus(403);
        $this->actingAs($agentA)->get(route('sub-agent.edit', $subAgentB))->assertStatus(403);
        $this->actingAs($agentA)->put(route('sub-agent.update', $subAgentB), [
            'name' => 'Hacked Name',
            'address' => 'Hacked Address',
            'phone_number' => '08123456789',
            'agent_id' => $agentA->id,
        ])->assertStatus(403);
        $this->actingAs($agentA)->delete(route('sub-agent.destroy', $subAgentB))->assertStatus(403);
    }

    public function test_s3_agent_cannot_tamper_agent_id_in_order_store(): void
    {
        $agentA = $this->createAgentUser('Agent S3A', 'agent_s3a@example.com');
        $agentB = $this->createAgentUser('Agent S3B', 'agent_s3b@example.com');

        $product = Product::first();
        $subAgentA = SubAgent::create([
            'agent_id' => $agentA->id,
            'name' => 'Sub Agent S3A',
            'address' => 'Address S3A',
            'phone_number' => '08123456789',
        ]);

        // Agent A attempts to submit order with Agent B's ID
        $orderNumber = 'ORD-S3-' . time();
        $response = $this->actingAs($agentA)->post(route('order.store'), [
            'agent_id' => $agentB->id,
            'order_number' => $orderNumber,
            'order_date' => now()->toDateString(),
            'total_price' => 100000,
            'products' => json_encode([[
                'productId' => $product->id,
                'subAgentId' => $subAgentA->id,
                'qty' => 1,
                'subTotal' => 100000,
            ]]),
        ]);

        $createdOrder = Order::where('order_number', $orderNumber)->first();
        $this->assertNotNull($createdOrder);
        // agent_id must be Agent A's ID, not Agent B's ID
        $this->assertEquals($agentA->id, $createdOrder->agent_id);
    }

    public function test_s4_public_debug_routes_removed(): void
    {
        $this->assertFalse(Route::has('sendmail'));
        $this->get('/sendmail')->assertStatus(404);
        $this->get('/testview')->assertStatus(404);
    }

    public function test_s5_get_image_controller_protects_administration_and_blocks_traversal(): void
    {
        // 1. Path traversal attempt returns 400
        try {
            (new \App\Http\Controllers\GetImageController())->displayImage('proofs', '../secret.jpg');
            $this->fail('Expected HttpException 400 not thrown');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            $this->assertEquals(400, $e->getStatusCode());
        }

        // 2. Unauthenticated access to administration folder returns 403
        $this->get('/storage/images/administration/ktp_sample.jpg')->assertStatus(403);

        // 3. Authenticated Agent A accessing Agent B's document returns 403
        $agentA = $this->createAgentUser('Agent ImgA', 'agent_imga@example.com');
        $agentB = $this->createAgentUser('Agent ImgB', 'agent_imgb@example.com');

        Administration::create([
            'user_id' => $agentB->id,
            'ktp' => 'ktp_b.jpg',
            'sPerjanjian' => 'agreement_b.jpg',
        ]);

        $this->actingAs($agentA)->get('/storage/images/administration/ktp_b.jpg')->assertStatus(403);

        // 4. Owner agent or super_admin can access (returns 404 if file does not exist on disk, but passes 403 auth)
        $this->actingAs($agentB)->get('/storage/images/administration/ktp_b.jpg')->assertStatus(404);

        $admin = User::role('super_admin')->first();
        if ($admin) {
            $this->actingAs($admin)->get('/storage/images/administration/ktp_b.jpg')->assertStatus(404);
        }
    }

    public function test_s6_active_middleware_blocks_inactive_admin(): void
    {
        $this->getOrCreateRole('admin');
        $inactiveAdmin = User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Inactive Admin',
            'email' => 'inactive_admin@example.com',
            'password' => bcrypt('password'),
            'active' => 0,
            'email_verified_at' => now(),
        ]);
        $inactiveAdmin->assignRole('admin');

        $response = $this->actingAs($inactiveAdmin)->get(route('dashboard-admin'));
        // Inactive user should be redirected to route 'nonactive'
        $response->assertRedirect(route('nonactive'));
    }
}
