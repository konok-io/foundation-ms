<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\MonthlyContribution;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContributionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_contribution_list()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('contributions.view');

        $response = $this->actingAs($user)->get('/admin/contributions');
        $response->assertStatus(200);
    }

    public function test_can_create_contribution()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['contributions.view', 'contributions.create']);

        $member = Member::factory()->create();

        $response = $this->actingAs($user)->post('/admin/contributions', [
            'member_id' => $member->id,
            'year' => date('Y'),
            'month' => date('n'),
            'amount' => 100,
            'due_date' => date('Y-m-d'),
        ]);

        $response->assertRedirect('/admin/contributions');
        $this->assertDatabaseHas('monthly_contributions', [
            'member_id' => $member->id,
            'amount' => 100,
        ]);
    }

    public function test_can_view_contribution_details()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('contributions.view');

        $member = Member::factory()->create();
        $contribution = MonthlyContribution::factory()->create(['member_id' => $member->id]);

        $response = $this->actingAs($user)->get("/admin/contributions/{$contribution->id}");
        $response->assertStatus(200);
    }

    public function test_can_update_contribution()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['contributions.view', 'contributions.edit']);

        $contribution = MonthlyContribution::factory()->create(['amount' => 100]);

        $response = $this->actingAs($user)->put("/admin/contributions/{$contribution->id}", [
            'member_id' => $contribution->member_id,
            'year' => $contribution->year,
            'month' => $contribution->month,
            'amount' => 150,
            'due_date' => $contribution->due_date?->format('Y-m-d'),
            'status' => $contribution->status,
        ]);

        $response->assertRedirect("/admin/contributions/{$contribution->id}");
        $this->assertDatabaseHas('monthly_contributions', [
            'id' => $contribution->id,
            'amount' => 150,
        ]);
    }

    public function test_can_record_payment()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['contributions.view', 'contributions.edit']);

        $contribution = MonthlyContribution::factory()->pending()->create([
            'amount' => 100,
            'paid_amount' => 0,
            'due_amount' => 100,
        ]);

        $response = $this->actingAs($user)->post("/admin/contributions/{$contribution->id}/payment", [
            'paid_amount' => 100,
            'payment_method' => 'cash',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('monthly_contributions', [
            'id' => $contribution->id,
            'paid_amount' => 100,
            'status' => 'paid',
        ]);
    }

    public function test_can_delete_pending_contribution()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['contributions.view', 'contributions.delete']);

        $contribution = MonthlyContribution::factory()->pending()->create();

        $response = $this->actingAs($user)->delete("/admin/contributions/{$contribution->id}");

        $response->assertRedirect('/admin/contributions');
        $this->assertDatabaseMissing('monthly_contributions', ['id' => $contribution->id]);
    }

    public function test_cannot_delete_paid_contribution()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['contributions.view', 'contributions.delete']);

        $contribution = MonthlyContribution::factory()->paid()->create();

        $response = $this->actingAs($user)->delete("/admin/contributions/{$contribution->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('monthly_contributions', ['id' => $contribution->id]);
    }

    public function test_can_generate_monthly_contributions()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['contributions.view', 'contributions.create']);

        $member = Member::factory()->count(3)->create();

        $response = $this->actingAs($user)->post('/admin/contributions/generate', [
            'year' => date('Y'),
            'month' => 1,
            'amount' => 100,
            'due_date' => date('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertEquals(3, MonthlyContribution::count());
    }

    public function test_contribution_requires_member()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['contributions.view', 'contributions.create']);

        $response = $this->actingAs($user)->post('/admin/contributions', [
            'year' => date('Y'),
            'month' => date('n'),
            'amount' => 100,
        ]);

        $response->assertSessionHasErrors('member_id');
    }
}
