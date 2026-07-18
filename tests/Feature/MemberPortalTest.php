<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_access_dashboard()
    {
        $user = User::factory()->create();
        $member = Member::factory()->create(['email' => $user->email]);
        
        $response = $this->actingAs($user)->get('/member/dashboard');
        $response->assertStatus(200);
    }

    public function test_member_can_view_profile()
    {
        $user = User::factory()->create();
        $member = Member::factory()->create(['email' => $user->email]);
        
        $response = $this->actingAs($user)->get('/member/profile');
        $response->assertStatus(200);
        $response->assertSee($member->name);
    }

    public function test_member_can_update_profile()
    {
        $user = User::factory()->create();
        $member = Member::factory()->create(['email' => $user->email]);
        
        $response = $this->actingAs($user)->put('/member/profile', [
            'name' => 'Updated Name',
            'father_name' => $member->father_name,
            'mother_name' => $member->mother_name,
            'date_of_birth' => $member->date_of_birth->format('Y-m-d'),
            'gender' => $member->gender,
            'mobile' => '+8801700123456',
            'present_address' => 'Updated Address',
            'join_date' => $member->join_date->format('Y-m-d'),
        ]);
        
        $response->assertRedirect('/member/profile');
        $this->assertDatabaseHas('members', ['id' => $member->id, 'mobile' => '+8801700123456']);
    }

    public function test_member_can_view_change_password()
    {
        $user = User::factory()->create();
        $member = Member::factory()->create(['email' => $user->email]);
        
        $response = $this->actingAs($user)->get('/member/change-password');
        $response->assertStatus(200);
    }

    public function test_member_can_change_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password'),
        ]);
        $member = Member::factory()->create(['email' => $user->email]);
        
        $response = $this->actingAs($user)->put('/member/change-password', [
            'current_password' => 'old-password',
            'new_password' => 'new-password123',
            'new_password_confirmation' => 'new-password123',
        ]);
        
        $response->assertRedirect('/member/profile');
        $this->assertTrue(\Hash::check('new-password123', $user->fresh()->password));
    }

    public function test_member_can_view_id_card()
    {
        $user = User::factory()->create();
        $member = Member::factory()->create(['email' => $user->email]);
        
        $response = $this->actingAs($user)->get('/member/card');
        $response->assertStatus(200);
        $response->assertSee($member->member_id);
    }

    public function test_member_can_view_payments()
    {
        $user = User::factory()->create();
        $member = Member::factory()->create(['email' => $user->email]);
        
        $response = $this->actingAs($user)->get('/member/payments');
        $response->assertStatus(200);
    }

    public function test_member_can_view_contributions()
    {
        $user = User::factory()->create();
        $member = Member::factory()->create(['email' => $user->email]);
        
        $response = $this->actingAs($user)->get('/member/contributions');
        $response->assertStatus(200);
    }

    public function test_member_can_view_notices()
    {
        $user = User::factory()->create();
        $member = Member::factory()->create(['email' => $user->email]);
        
        $response = $this->actingAs($user)->get('/member/notices');
        $response->assertStatus(200);
    }

    public function test_member_can_view_donations()
    {
        $user = User::factory()->create();
        $member = Member::factory()->create(['email' => $user->email]);
        
        $response = $this->actingAs($user)->get('/member/donations');
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_member_portal()
    {
        $response = $this->get('/member/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_user_without_member_profile_sees_error()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/member/dashboard');
        $response->assertRedirect('/');
    }
}
