<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_guest_cannot_access_index(): void
    {
        $restaurant = Restaurant::factory()->create();


        $response = $this->get(route('restaurants.reviews.index', $restaurant));

        $response->assertRedirect('/login');
    }

    public function test_not_subscribed_user_can_access_index(){
        $restaurant = Restaurant::factory()->create();


        $user = User::factory()->create();


        $response = $this->actingAs($user)->get(route('restaurants.reviews.index', $restaurant));

        $response->assertStatus(200);
    }

    public function test_subscribed_user_acn_access_index(){
        $restaurant = Restaurant::factory()->create();


        $user = User::factory()->create();

        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

        $response = $this->actingAs($user)->get(route('restaurants.reviews.index', $restaurant));

        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_index(){
        $restaurant = Restaurant::factory()->create();

        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('restaurants.reviews.index', $restaurant));

        $response->assertredirect('admin/home');
    }

    public function test_guest_cannot_access_create(): void
    {
        $restaurant = Restaurant::factory()->create();


        $response = $this->get(route('restaurants.reviews.create', $restaurant));

        $response->assertRedirect('/login');
    }

    public function test_not_subscribed_user_cannot_access_create(){
        $restaurant = Restaurant::factory()->create();


        $user = User::factory()->create();


        $response = $this->actingAs($user)->get(route('restaurants.reviews.create', $restaurant));

        $response->assertRedirect('subscription/create');
    }

    public function test_subscribed_user_can_access_create(){
        $restaurant = Restaurant::factory()->create();


        $user = User::factory()->create();

        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

        $response = $this->actingAs($user)->get(route('restaurants.reviews.create', $restaurant));

        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_create(){
        $restaurant = Restaurant::factory()->create();

        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('restaurants.reviews.create', $restaurant));

        $response->assertredirect('admin/home');
    }
    
    public function test_guest_cannot_store(): void
    {
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $review = Review::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ])->toArray();


        $response = $this->post(route('restaurants.reviews.store', $restaurant), $review);

        $this->assertDatabaseMissing('reviews', $review);
    }

    public function test_not_subscribed_user_cannot_store(){
        $restaurant = Restaurant::factory()->create();


        $user = User::factory()->create();

        $review = Review::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ])->toArray();


        $response = $this->actingAs($user)->post(route('restaurants.reviews.store', $restaurant), $review);

        $this->assertDatabaseMissing('reviews', $review);
    }

    public function test_subscribed_user_can_store(){
        $restaurant = Restaurant::factory()->create();


        $user = User::factory()->create();

        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

        $review = 
        // Review::factory()->create([
        //     'restaurant_id' => $restaurant->id,
        //     'user_id' => $user->id
        // ])->toArray();
        ['score' => 1,
        'content' => 'テスト',
        'restaurant_id' => $restaurant->id];

        

        $response = $this->actingAs($user)->post(route('restaurants.reviews.store', $restaurant), $review);

        $this->assertDatabaseHas('reviews', $review);
    }

    public function test_admin_cannot_store(){
        $restaurant = Restaurant::factory()->create();

        $adminUser = Admin::factory()->create();

        $user = User::factory()->create();

        $review = Review::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ])->toArray();

        $response = $this->actingAs($adminUser, 'admin')->post(route('restaurants.reviews.store', $restaurant), $review);

        $this->assertDatabaseMissing('reviews', $review);
    }

    
    public function test_guest_cannot_access_edit(): void
    {
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $review = Review::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);


        $response = $this->get(route('restaurants.reviews.edit', [$restaurant, $review]));

        $response->assertRedirect('/login');
    }

    public function test_not_subscribed_user_cannot_access_edit(){
        $restaurant = Restaurant::factory()->create();


        $user = User::factory()->create();

        $review = Review::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);


        $response = $this->actingAs($user)->get(route('restaurants.reviews.edit', [$restaurant, $review]));

        $response->assertRedirect('subscription/create');
    }
    public function test_subscribed_other_user_cannot_access_edit(){
        $restaurant = Restaurant::factory()->create();

        $otherUser = User::factory()->create();

       


        $user = User::factory()->create();

        $review = Review::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $otherUser->id
        ]);

        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

        $response = $this->actingAs($user)->get(route('restaurants.reviews.edit', [$restaurant, $review]));

        $response->assertRedirect("restaurants/{$restaurant->id}/reviews", $restaurant);
    }

    public function test_subscribed_user_can_access_edit(){
        $restaurant = Restaurant::factory()->create();


        $user = User::factory()->create();

        $review = Review::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

        $response = $this->actingAs($user)->get(route('restaurants.reviews.edit', [$restaurant, $review]));

        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_edit(){
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $adminUser = Admin::factory()->create();

        $review = Review::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($adminUser, 'admin')->get(route('restaurants.reviews.edit', [$restaurant, $review]));

        $response->assertredirect('admin/home');
}
public function test_guest_cannot_update(): void
{
    $restaurant = Restaurant::factory()->create();

    $user = User::factory()->create();

    $old_review = Review::factory()->create([
        'restaurant_id' => $restaurant->id,
        'user_id' => $user->id
    ]);

    $review = 
    ['score' => 2,
    'content' => 'シンテスト',
    'restaurant_id' => $restaurant->id];


    $response = $this->patch(route('restaurants.reviews.update', [$restaurant, $old_review]), $review);

    $this->assertDatabaseMissing('reviews', $review);
}

public function test_not_subscribed_user_cannot_update(){
    $restaurant = Restaurant::factory()->create();


    $user = User::factory()->create();

    $old_review = Review::factory()->create([
        'restaurant_id' => $restaurant->id,
        'user_id' => $user->id
    ]);

    $review = 
    ['score' => 2,
    'content' => 'シンテスト',
    'restaurant_id' => $restaurant->id];


    $response = $this->actingAs($user)->patch(route('restaurants.reviews.update', [$restaurant, $old_review]), $review);

    $this->assertDatabaseMissing('reviews', $review);
}


public function test_subscribed_other_user_cannot_update(){
    $restaurant = Restaurant::factory()->create();

    $otherUser = User::factory()->create();

    $user = User::factory()->create();

    $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

    $old_review = Review::factory()->create([
        'restaurant_id' => $restaurant->id,
        'user_id' => $otherUser->id
    ]);

    $review = 
    ['score' => 2,
    'content' => 'シンテスト',
    'restaurant_id' => $restaurant->id];

    

    $response = $this->actingAs($user)->patch(route('restaurants.reviews.update', [$restaurant, $old_review]), $review);

    $this->assertDatabaseMissing('reviews', $review);
}
public function test_subscribed_user_can_update(){
    $restaurant = Restaurant::factory()->create();


    $user = User::factory()->create();

    $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

    $old_review = Review::factory()->create([
        'restaurant_id' => $restaurant->id,
        'user_id' => $user->id
    ]);

    $review = 
    ['score' => 2,
    'content' => 'シンテスト',
    'restaurant_id' => $restaurant->id];

    

    $response = $this->actingAs($user)->patch(route('restaurants.reviews.update', [$restaurant, $old_review]), $review);

    $this->assertDatabaseHas('reviews', $review);
}

public function test_admin_cannot_update(){
    $restaurant = Restaurant::factory()->create();

    $adminUser = Admin::factory()->create();

    $user = User::factory()->create();

    $old_review = Review::factory()->create([
        'restaurant_id' => $restaurant->id,
        'user_id' => $user->id
    ]);

    $review = 
    ['score' => 2,
    'content' => 'シンテスト',
    'restaurant_id' => $restaurant->id];

    $response = $this->actingAs($adminUser, 'admin')->patch(route('restaurants.reviews.update', [$restaurant, $old_review], $review));

    $this->assertDatabaseMissing('reviews', $review);
}

public function test_guest_cannot_destroy(): void
{
    $restaurant = Restaurant::factory()->create();

    $user = User::factory()->create();

    $review = Review::factory()->create([
        'restaurant_id' => $restaurant->id,
        'user_id' => $user->id
    ]);



    $response = $this->delete(route('restaurants.reviews.destroy', [$restaurant, $review]));

    $this->assertDatabaseHas('reviews', ['id' => $review->id]);
}

public function test_not_subscribed_user_cannot_destroy(){
    $restaurant = Restaurant::factory()->create();


    $user = User::factory()->create();

    $review = Review::factory()->create([
        'restaurant_id' => $restaurant->id,
        'user_id' => $user->id
    ]);

    $response = $this->actingAs($user)->delete(route('restaurants.reviews.destroy', [$restaurant, $review]));

    $this->assertDatabaseHas('reviews', ['id' => $review->id]);
}


public function test_subscribed_other_user_cannot_destroy(){
    $restaurant = Restaurant::factory()->create();

    $otherUser = User::factory()->create();

    $user = User::factory()->create();

    $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

    $review = Review::factory()->create([
        'restaurant_id' => $restaurant->id,
        'user_id' => $otherUser->id
    ]);


    

    $response = $this->actingAs($user)->delete(route('restaurants.reviews.destroy', [$restaurant, $review]));

    $this->assertDatabaseHas('reviews', ['id' => $review->id]);
}
public function test_subscribed_user_can_destroy(){
    $restaurant = Restaurant::factory()->create();


    $user = User::factory()->create();

    $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

    $review = Review::factory()->create([
        'restaurant_id' => $restaurant->id,
        'user_id' => $user->id
    ]);

  

    

    $response = $this->actingAs($user)->delete(route('restaurants.reviews.destroy', [$restaurant, $review]));

    $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
}

public function test_admin_cannot_destroy(){
    $restaurant = Restaurant::factory()->create();

    $adminUser = Admin::factory()->create();

    $user = User::factory()->create();

    $review = Review::factory()->create([
        'restaurant_id' => $restaurant->id,
        'user_id' => $user->id
    ]);

 

    $response = $this->actingAs($adminUser, 'admin')->delete(route('restaurants.reviews.destroy', [$restaurant, $review]));

    $this->assertDatabaseHas('reviews', ['id' => $review->id]);
}
}