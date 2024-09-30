<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{


    
    /**
     * A basic feature test example.
     */
    public function test_guest_cannot_access_admin_categories_index()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('admin.categories.index'));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_categories_index()
    {
        $category = Category::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.categories.index'));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_categories_index()
    {
        $category = Category::factory()->create();

        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.categories.index'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_categories_store()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('admin.categories.store'));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_categories_store()
    {
        $category = Category::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.categories.store'));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_categories_store()
    {
        $category = Category::factory()->create();

        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.categories.store'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_categories_update()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('admin.categories.store'));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_categories_update()
    {
        $category = Category::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.categories.store'));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_categories_update()
    {
        $category = Category::factory()->create();
        
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.categories.store'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_categories_destroy()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('admin.categories.store'));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_categories_destroy()
    {
        $category = Category::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.categories.store'));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_categories_destroy()
    {
        $category = Category::factory()->create();
        
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.categories.store'));

        $response->assertStatus(200);
    }

}
