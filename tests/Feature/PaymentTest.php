<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_payment_list()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('payments.view');

        $response = $this->actingAs($user)->get('/admin/payments');
        $response->assertStatus(200);
    }

    public function test_can_view_payment_details()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('payments.view');

        $payment = Payment::factory()->create();

        $response = $this->actingAs($user)->get("/admin/payments/{$payment->id}");
        $response->assertStatus(200);
    }

    public function test_can_process_refund()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['payments.view', 'payments.refund']);

        $payment = Payment::factory()->completed()->stripe()->create();

        $response = $this->actingAs($user)->post("/admin/payments/{$payment->id}/refund", [
            'amount' => $payment->amount,
            'reason' => 'Customer requested refund',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'refunded',
        ]);
    }

    public function test_cannot_refund_pending_payment()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['payments.view', 'payments.refund']);

        $payment = Payment::factory()->pending()->create();

        $response = $this->actingAs($user)->post("/admin/payments/{$payment->id}/refund", [
            'reason' => 'Test refund',
        ]);

        $response->assertSessionHas('error');
    }

    public function test_payment_requires_authentication()
    {
        $response = $this->get('/admin/payments');
        $response->assertRedirect('/admin/login');
    }

    public function test_payment_export()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['payments.view', 'payments.export']);

        Payment::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/admin/payments/export');
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
    }
}
