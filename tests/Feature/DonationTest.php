<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_donation_list()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('donations.view');

        $response = $this->actingAs($user)->get('/admin/donations');
        $response->assertStatus(200);
    }

    public function test_can_view_donation_create_form()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('donations.create');

        $response = $this->actingAs($user)->get('/admin/donations/create');
        $response->assertStatus(200);
    }

    public function test_can_create_donation()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('donations.create');

        $data = [
            'donor_name' => 'Test Donor',
            'email' => 'test@example.com',
            'phone' => '+966501234567',
            'amount' => 500,
            'purpose' => 'medical',
            'payment_method' => 'cash',
            'status' => 'completed',
        ];

        $response = $this->actingAs($user)->post('/admin/donations', $data);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('donations', [
            'donor_name' => 'Test Donor',
            'amount' => 500,
        ]);
    }

    public function test_can_view_donation_details()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('donations.view');

        $donation = Donation::factory()->create();

        $response = $this->actingAs($user)->get("/admin/donations/{$donation->id}");
        $response->assertStatus(200);
    }

    public function test_can_update_donation()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('donations.edit');

        $donation = Donation::factory()->create();

        $data = [
            'donor_name' => 'Updated Donor',
            'email' => 'updated@example.com',
            'phone' => '+966501234567',
            'amount' => 1000,
            'purpose' => 'education',
            'payment_method' => 'bank_transfer',
            'status' => 'completed',
        ];

        $response = $this->actingAs($user)->put("/admin/donations/{$donation->id}", $data);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('donations', [
            'id' => $donation->id,
            'donor_name' => 'Updated Donor',
            'amount' => 1000,
        ]);
    }

    public function test_can_delete_donation()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('donations.delete');

        $donation = Donation::factory()->create();

        $response = $this->actingAs($user)->delete("/admin/donations/{$donation->id}");
        
        $response->assertRedirect();
        $this->assertDatabaseMissing('donations', [
            'id' => $donation->id,
        ]);
    }

    public function test_donation_requires_authentication()
    {
        $response = $this->get('/admin/donations');
        $response->assertRedirect('/admin/login');
    }

    public function test_can_access_public_donation_form()
    {
        $response = $this->get('/donate');
        $response->assertStatus(200);
    }

    public function test_can_submit_public_donation()
    {
        $data = [
            'donor_name' => 'Public Donor',
            'email' => 'public@example.com',
            'phone' => '+966501234567',
            'amount' => 200,
            'purpose' => 'general',
            'is_anonymous' => false,
        ];

        $response = $this->post('/donate', $data);
        
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('donations', [
            'donor_name' => 'Public Donor',
            'amount' => 200,
        ]);
    }

    public function test_donation_export()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['donations.view', 'donations.export']);

        Donation::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/admin/donations/export');
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
    }
}
