<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RestaurantTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_guest_cannot_access_admin_restaurants_index()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.index'));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_restaurants_index()
    {
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.index'));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_restaurants_index()
    {
        $restaurant = Restaurant::factory()->create();

        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.restaurants.index'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_restaurants_show()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.show', $restaurant));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_restaurants_show()
    {
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.show', $restaurant));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_restaurants_show()
    {
        $restaurant = Restaurant::factory()->create();
        
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.restaurants.show', $restaurant));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_restaurants_create()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.create'));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_restaurants_create()
    {
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.create'));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_restaurants_create()
    {
        $restaurant = Restaurant::factory()->create();
        
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.restaurants.create'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_restaurants_store()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.store'));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_restaurants_store()
    {
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.store'));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_restaurants_store()
    {
        $restaurant = Restaurant::factory()->create();
        
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.restaurants.store'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_restaurants_edit()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.edit'));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_restaurants_edit()
    {
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.edit'));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_restaurants_edit()
    {
        $restaurant = Restaurant::factory()->create();
        
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.restaurants.edit'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_restaurants_update()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.update'));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_restaurants_update()
    {
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.update'));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_restaurants_update()
    {
        $restaurant = Restaurant::factory()->create();
        
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.restaurants.update'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_restaurants_destroy()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.destroy'));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_restaurants_destroy()
    {
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.destroy'));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_restaurants_destroy()
    {
        $restaurant = Restaurant::factory()->create();
        
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.restaurants.destroy'));

        $response->assertStatus(200);
    }
}