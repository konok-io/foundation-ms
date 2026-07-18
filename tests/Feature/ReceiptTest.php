<?php

namespace Tests\Feature;

use App\Models\Receipt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceiptTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_receipt_list()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('receipts.view');

        $response = $this->actingAs($user)->get('/admin/receipts');
        $response->assertStatus(200);
    }

    public function test_can_view_receipt_details()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('receipts.view');

        $receipt = Receipt::factory()->create();

        $response = $this->actingAs($user)->get("/admin/receipts/{$receipt->id}");
        $response->assertStatus(200);
    }

    public function test_can_download_receipt_pdf()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['receipts.view', 'receipts.download']);

        $receipt = Receipt::factory()->create();

        $response = $this->actingAs($user)->get("/admin/receipts/{$receipt->id}/download");
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_can_verify_receipt()
    {
        $receipt = Receipt::factory()->create();

        $response = $this->get("/verify/receipt/{$receipt->receipt_no}");
        
        $response->assertStatus(200);
        $response->assertViewHas('verification');
    }

    public function test_invalid_receipt_shows_error()
    {
        $response = $this->get('/verify/receipt/INVALID-123');
        
        $response->assertStatus(200);
        $response->assertViewHas('verification', function ($verification) {
            return !$verification['valid'];
        });
    }

    public function test_receipt_export()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['receipts.view', 'receipts.export']);

        Receipt::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/admin/receipts/export');
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
    }

    public function test_receipt_requires_authentication()
    {
        $response = $this->get('/admin/receipts');
        $response->assertRedirect('/admin/login');
    }

    public function test_can_generate_unique_receipt_number()
    {
        $receipt1 = Receipt::factory()->create();
        $receipt2 = Receipt::factory()->create();

        $this->assertNotEquals($receipt1->receipt_no, $receipt2->receipt_no);
    }

    public function test_can_find_receipt_by_receipt_no()
    {
        $receipt = Receipt::factory()->create();

        $found = Receipt::findByReceiptNo($receipt->receipt_no);

        $this->assertNotNull($found);
        $this->assertEquals($receipt->id, $found->id);
    }
}
