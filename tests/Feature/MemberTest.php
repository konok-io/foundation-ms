<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_member_list()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('members.view');

        $response = $this->actingAs($user)->get('/admin/members');
        $response->assertStatus(200);
    }

    public function test_can_create_member()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['members.view', 'members.create']);

        $response = $this->actingAs($user)->post('/admin/members', [
            'name' => 'Test Member',
            'father_name' => 'Test Father',
            'mother_name' => 'Test Mother',
            'date_of_birth' => '1990-01-15',
            'gender' => 'male',
            'mobile' => '+8801700123456',
            'present_address' => 'Test Address, Dhaka',
            'join_date' => '2024-01-01',
        ]);

        $response->assertRedirect('/admin/members');
        $this->assertDatabaseHas('members', [
            'name' => 'Test Member',
            'mobile' => '+8801700123456',
        ]);
    }

    public function test_can_update_member()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['members.view', 'members.edit']);

        $member = Member::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user)->put("/admin/members/{$member->id}", [
            'name' => 'New Name',
            'father_name' => $member->father_name,
            'mother_name' => $member->mother_name,
            'date_of_birth' => $member->date_of_birth->format('Y-m-d'),
            'gender' => $member->gender,
            'mobile' => $member->mobile,
            'present_address' => $member->present_address,
            'join_date' => $member->join_date->format('Y-m-d'),
        ]);

        $response->assertRedirect("/admin/members/{$member->id}");
        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'name' => 'New Name',
        ]);
    }

    public function test_can_delete_member()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['members.view', 'members.delete']);

        $member = Member::factory()->create();

        $response = $this->actingAs($user)->delete("/admin/members/{$member->id}");

        $response->assertRedirect('/admin/members');
        $this->assertDatabaseMissing('members', ['id' => $member->id]);
    }

    public function test_can_view_member_profile()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('members.view');

        $member = Member::factory()->create();

        $response = $this->actingAs($user)->get("/admin/members/{$member->id}");
        $response->assertStatus(200);
        $response->assertSee($member->name);
    }

    public function test_member_id_auto_generated()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['members.view', 'members.create']);

        $response = $this->actingAs($user)->post('/admin/members', [
            'name' => 'Auto ID Member',
            'father_name' => 'Test Father',
            'mother_name' => 'Test Mother',
            'date_of_birth' => '1990-01-15',
            'gender' => 'male',
            'mobile' => '+8801700123456',
            'present_address' => 'Test Address, Dhaka',
            'join_date' => '2024-01-01',
        ]);

        $this->assertDatabaseHas('members', [
            'name' => 'Auto ID Member',
        ]);
        
        $member = Member::where('name', 'Auto ID Member')->first();
        $this->assertStringStartsWith('FMS-' . date('Y') . '-', $member->member_id);
    }

    public function test_member_requires_name()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['members.view', 'members.create']);

        $response = $this->actingAs($user)->post('/admin/members', [
            'father_name' => 'Test Father',
            'mother_name' => 'Test Mother',
            'date_of_birth' => '1990-01-15',
            'gender' => 'male',
            'mobile' => '+8801700123456',
            'present_address' => 'Test Address, Dhaka',
            'join_date' => '2024-01-01',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_can_view_member_card()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['members.view', 'members.card']);

        $member = Member::factory()->create();

        $response = $this->actingAs($user)->get("/admin/members/{$member->id}/card");
        $response->assertStatus(200);
        $response->assertSee($member->member_id);
    }

    public function test_can_search_members()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('members.view');

        $member = Member::factory()->create(['name' => 'Searchable Name']);

        $response = $this->actingAs($user)->get('/admin/members?search=Searchable');
        $response->assertStatus(200);
        $response->assertSee('Searchable Name');
    }
}
