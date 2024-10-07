<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

     //予約一覧ページ
    public function test_guest_cannot_access_index(): void
    {

        $response = $this->get(route('reservations.index'));

        $response->assertRedirect('/login');
    }

    public function test_not_subscribed_user_cannot_access_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('reservations.index'));

        $response->assertRedirect('subscription/create');
    }

    public function test_subscribed_user_can_access_index(): void
    {
        $user = User::factory()->create();

        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

        $response = $this->actingAs($user)->get(route('reservations.index'));

        $response->assertStatus(200);
    }

    public function test_admin_user_cannot_access_index(): void
    {
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('reservations.index'));

        $response->assertRedirect('admin/home');
    }

         //予約ページ
    public function test_guest_cannot_access_create(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ]);
     
        $response = $this->get(route('restaurants.reservations.create', $restaurant));
     
        $response->assertRedirect('/login');
    }
     
    public function test_not_subscribed_user_cannot_access_create(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ]);
     
        $response = $this->actingAs($user)->get(route('restaurants.reservations.create', $restaurant));
     
        $response->assertRedirect('subscription/create');
    }
     
    public function test_subscribed_user_can_access_create(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ]);
     
        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');
     
        $response = $this->actingAs($user)->get(route('restaurants.reservations.create', $restaurant));
     
        $response->assertStatus(200);
    }
     
    public function test_admin_user_cannot_access_create(): void
    {

        $user = User::factory()->create();

        $adminUser = Admin::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ]);
     
        $response = $this->actingAs($adminUser, 'admin')->get(route('restaurants.reservations.create', $restaurant));
     
        $response->assertRedirect('admin/home');
    }

         //予約機能
    public function test_guest_cannot_store(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ])->toArray();
     
        $response = $this->post(route('restaurants.reservations.store', $restaurant), $reservation);
     
        $this->assertDatabaseMissing('reservations', $reservation);
    }
     
    public function test_not_subscribed_user_cannot_store(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ])->toArray();
     
        $response = $this->actingAs($user)->post(route('restaurants.reservations.store', $restaurant), $reservation);
     
        $this->assertDatabaseMissing('reservations', $reservation);
        }
     
    public function test_subscribed_user_can_store(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $reservation =[
            'reservation_date' => '2025-11-11',
            'reservation_time' => '22:22',
            'number_of_people' => 2,
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ];
     
        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');
     
        $response = $this->actingAs($user)->post(route('restaurants.reservations.store',  $restaurant), $reservation);
     
        $this->assertDatabaseHas('reservations', $reservation =[
            'reserved_datetime' => '2025-11-11 22:22',
            'number_of_people' => 2,
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ]);
    }
     
    public function test_admin_user_cannot_store(): void
    {

        $user = User::factory()->create();
            
        $adminUser = Admin::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ])->toArray();
     
        $response = $this->actingAs($adminUser, 'admin')->post(route('restaurants.reservations.store',  $restaurant), $reservation);
     
        $this->assertDatabaseMissing('reservations', $reservation);
    }

         //予約削除
    public function test_guest_cannot_destroy(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ]);
     
        $response = $this->delete(route('reservations.destroy', $reservation->id));
     
        $this->assertDatabaseHas('reservations', ['id' => $reservation->id]);
    }
     
    public function test_not_subscribed_user_cannot_destroy(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ]);
     
        $response = $this->actingAs($user)->delete(route('reservations.destroy', $reservation->id));
     
        $this->assertDatabaseHas('reservations', ['id' => $reservation->id]);
    }
     
    public function test_subscribed_other_user_cannot_destroy(): void
    {
        $user = User::factory()->create();

        $otherUser = User::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $reservation =Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $otherUser->id
            ]);
     
        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');
     
        $response = $this->actingAs($user)->delete(route('reservations.destroy',  $reservation->id));
     
        $this->assertDatabaseHas('reservations', ['id' => $reservation->id]);
    }

    public function test_subscribed_user_can_destroy(): void
    {
        $user = User::factory()->create();


        $restaurant = Restaurant::factory()->create();

        $reservation =Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ]);
     
        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');
     
        $response = $this->actingAs($user)->delete(route('reservations.destroy',  $reservation->id));
     
        $this->assertDatabaseMissing('reservations',  ['id' => $reservation->id]);
    }
     
    public function test_admin_user_cannot_destroy(): void
    {

        $user = User::factory()->create();
            
        $adminUser = Admin::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
            ]);
     
        $response = $this->actingAs($adminUser, 'admin')->delete(route('reservations.destroy', $reservation->id));
     
        $this->assertDatabaseHas('reservations', ['id' => $reservation->id]);
        }
     


}
