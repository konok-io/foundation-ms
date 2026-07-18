<?php

namespace Tests\Feature;

use App\Models\EmergencyCollection;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmergencyCollectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_emergency_collection_list()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('emergency_collections.view');

        $response = $this->actingAs($user)->get('/admin/emergency-collections');
        $response->assertStatus(200);
    }

    public function test_can_create_emergency_collection()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['emergency_collections.view', 'emergency_collections.create']);

        $response = $this->actingAs($user)->post('/admin/emergency-collections', [
            'title' => 'Flood Relief Fund',
            'type' => 'flood_relief',
            'target_amount' => 50000,
            'amount_per_member' => 100,
            'start_date' => date('Y-m-d'),
        ]);

        $response->assertRedirect('/admin/emergency-collections');
        $this->assertDatabaseHas('emergency_collections', [
            'title' => 'Flood Relief Fund',
            'type' => 'flood_relief',
        ]);
    }

    public function test_can_view_emergency_collection_details()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('emergency_collections.view');

        $collection = EmergencyCollection::factory()->create();

        $response = $this->actingAs($user)->get("/admin/emergency-collections/{$collection->id}");
        $response->assertStatus(200);
    }

    public function test_can_update_emergency_collection()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['emergency_collections.view', 'emergency_collections.edit']);

        $collection = EmergencyCollection::factory()->create(['title' => 'Old Title']);

        $response = $this->actingAs($user)->put("/admin/emergency-collections/{$collection->id}", [
            'title' => 'New Title',
            'type' => $collection->type,
            'target_amount' => $collection->target_amount,
            'start_date' => $collection->start_date->format('Y-m-d'),
            'status' => $collection->status,
        ]);

        $response->assertRedirect("/admin/emergency-collections/{$collection->id}");
        $this->assertDatabaseHas('emergency_collections', [
            'id' => $collection->id,
            'title' => 'New Title',
        ]);
    }

    public function test_can_delete_draft_emergency_collection()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['emergency_collections.view', 'emergency_collections.delete']);

        $collection = EmergencyCollection::factory()->create(['status' => 'draft']);

        $response = $this->actingAs($user)->delete("/admin/emergency-collections/{$collection->id}");

        $response->assertRedirect('/admin/emergency-collections');
        $this->assertDatabaseMissing('emergency_collections', ['id' => $collection->id]);
    }

    public function test_cannot_delete_collection_with_collected_amounts()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['emergency_collections.view', 'emergency_collections.delete']);

        $collection = EmergencyCollection::factory()->create(['collected_amount' => 5000]);

        $response = $this->actingAs($user)->delete("/admin/emergency-collections/{$collection->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('emergency_collections', ['id' => $collection->id]);
    }

    public function test_can_close_emergency_collection()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['emergency_collections.view', 'emergency_collections.edit']);

        $collection = EmergencyCollection::factory()->active()->create();

        $response = $this->actingAs($user)->post("/admin/emergency-collections/{$collection->id}/close");

        $response->assertRedirect();
        $this->assertDatabaseHas('emergency_collections', [
            'id' => $collection->id,
            'status' => 'closed',
        ]);
    }

    public function test_can_assign_members_to_collection()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['emergency_collections.view', 'emergency_collections.edit']);

        $collection = EmergencyCollection::factory()->active()->create([
            'amount_per_member' => 100,
        ]);
        
        Member::factory()->count(3)->create(['status' => true]);

        $response = $this->actingAs($user)->post("/admin/emergency-collections/{$collection->id}/assign-members");

        $response->assertRedirect();
        $this->assertEquals(3, $collection->fresh()->payments()->count());
    }

    public function test_emergency_collection_requires_title()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['emergency_collections.view', 'emergency_collections.create']);

        $response = $this->actingAs($user)->post('/admin/emergency-collections', [
            'type' => 'medical',
            'target_amount' => 5000,
            'start_date' => date('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('title');
    }
}
