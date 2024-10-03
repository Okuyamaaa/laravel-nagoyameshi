<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_guest_can_access_index(): void
    {
        $restaurants = Restaurant::factory()->create();

        $response = $this->get(route('restaurants.index'));

        $response->assertStatus(200);
    }

    public function test_user_can_access_index(){

        $restaurants = Restaurant::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('restaurants.index'));

        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_index(){

        $restaurants = Restaurant::factory()->create();

        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('restaurants.index'));

        $response->assertRedirect('admin/home');
    }

    public function test_guest_can_access_show(): void
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('restaurants.show', $restaurant));

        $response->assertStatus(200);
    }

    public function test_user_can_access_show(){

        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('restaurants.show', $restaurant));

        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_show(){

        $restaurant = Restaurant::factory()->create();

        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('restaurants.show', $restaurant));

        $response->assertRedirect('admin/home');
    }
}
