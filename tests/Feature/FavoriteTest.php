<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_guest_cannot_access_index(): void
    {


        $response = $this->get(route('favorites.index'));

        $response->assertRedirect('/login');
    }

    public function test_not_subscribed_user_cannot_access_index(): void
    {
        $user = User::factory()->create();


        $response = $this->actingAs($user)->get(route('favorites.index'));

        $response->assertRedirect('subscription/create');
    }

    public function test_subscribed_user_can_access_index(): void
    {
        $user = User::factory()->create();

        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

        $response = $this->actingAs($user)->get(route('favorites.index'));

        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_index(): void
    {
        $adminUser = Admin::factory()->create();

        

        $response = $this->actingAs($adminUser, 'admin')->get(route('favorites.index'));

        $response->assertRedirect('admin/home');
    }

    public function test_guest_cannot_store(): void
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()->create();
        $restaurant_user =[
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ];


        $response = $this->post(route('favorites.store', $restaurant->id));

        $this->assertDatabaseMissing('restaurant_user', $restaurant_user);
    }

    public function test_not_subscribed_user_cannot_store(): void
    {

        $restaurant = Restaurant::factory()->create();
        $user = User::factory()->create();
        $restaurant_user =[
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ];


        $response = $this->actingAs($user)->post(route('favorites.store', $restaurant->id));

        $this->assertDatabaseMissing('restaurant_user', $restaurant_user);
    }

    public function test_subscribed_user_can_store(): void
    {

  
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()->create();
        $restaurant_user =[
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ];


        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

        $response = $this->actingAs($user)->post(route('favorites.store', $restaurant->id));

        $this->assertDatabaseHas('restaurant_user', $restaurant_user);
    }

    public function test_admin_cannot_store(): void
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()->create();
        $restaurant_user =[
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ];

        $adminUser = Admin::factory()->create();
        $response = $this->actingAs($adminUser, 'admin')->post(route('favorites.store', $restaurant->id));

        $this->assertDatabaseMissing('restaurant_user', $restaurant_user);
    }

    public function test_guest_cannot_destroy(): void
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()->create();
        $restaurant_user =[
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ];

        $restaurant->users()->attach($user->id);

        $response = $this->delete(route('favorites.store', $restaurant->id));

        $this->assertDatabaseHas('restaurant_user', $restaurant_user);
    }

    public function test_not_subscribed_user_cannot_destroy(): void
    {

        $restaurant = Restaurant::factory()->create();
        $user = User::factory()->create();
        $restaurant_user =[
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ];

        $restaurant->users()->attach($user->id);

        $response = $this->actingAs($user)->delete(route('favorites.store', $restaurant->id));

        $this->assertDatabaseHas('restaurant_user', $restaurant_user);
    }

    public function test_subscribed_user_can_destroy(): void
    {

  
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()->create();
        $restaurant_user =[
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ];

        $restaurant->users()->attach($user->id);


        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

        $response = $this->actingAs($user)->delete(route('favorites.store', $restaurant->id));

        $this->assertDatabaseMissing('restaurant_user', $restaurant_user);
    }

    public function test_admin_cannot_destroy(): void
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()->create();
        $restaurant_user =[
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ];

        $restaurant->users()->attach($user->id);

        $adminUser = Admin::factory()->create();
        $response = $this->actingAs($adminUser, 'admin')->delete(route('favorites.store', $restaurant->id));

        $this->assertDatabaseHas('restaurant_user', $restaurant_user);
    }
    
}
