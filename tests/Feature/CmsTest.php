<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_cms_index()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('settings.cms');

        $response = $this->actingAs($user)->get('/admin/cms');
        $response->assertStatus(200);
    }

    public function test_can_create_cms_page()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('settings.cms');

        $response = $this->actingAs($user)->post('/admin/cms', [
            'title' => 'Test Page',
            'slug' => 'test-page',
            'content' => '<p>Test content</p>',
            'status' => true,
        ]);

        $response->assertRedirect('/admin/cms');
        $this->assertDatabaseHas('cms_pages', [
            'title' => 'Test Page',
            'slug' => 'test-page',
        ]);
    }

    public function test_can_update_cms_page()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('settings.cms');

        $page = CmsPage::factory()->create([
            'title' => 'Old Title',
        ]);

        $response = $this->actingAs($user)->put("/admin/cms/{$page->id}", [
            'title' => 'New Title',
            'slug' => $page->slug,
            'content' => '<p>New content</p>',
            'status' => true,
        ]);

        $response->assertRedirect('/admin/cms');
        $this->assertDatabaseHas('cms_pages', [
            'id' => $page->id,
            'title' => 'New Title',
        ]);
    }

    public function test_can_delete_cms_page()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('settings.cms');

        $page = CmsPage::factory()->create();

        $response = $this->actingAs($user)->delete("/admin/cms/{$page->id}");

        $response->assertRedirect('/admin/cms');
        $this->assertDatabaseMissing('cms_pages', ['id' => $page->id]);
    }

    public function test_can_view_public_page()
    {
        $page = CmsPage::factory()->create([
            'slug' => 'test-public-page',
            'status' => true,
        ]);

        $response = $this->get('/page/test-public-page');
        $response->assertStatus(200);
        $response->assertSee($page->title);
    }

    public function test_cannot_view_inactive_page()
    {
        $page = CmsPage::factory()->create([
            'slug' => 'inactive-page',
            'status' => false,
        ]);

        $response = $this->get('/page/inactive-page');
        $response->assertStatus(404);
    }

    public function test_cms_page_requires_title()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('settings.cms');

        $response = $this->actingAs($user)->post('/admin/cms', [
            'slug' => 'test-page',
            'content' => '<p>Test content</p>',
        ]);

        $response->assertSessionHasErrors('title');
    }
}
