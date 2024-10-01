<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\RegularHoliday;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RestaurantTest extends TestCase
{

 use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_guest_cannot_access_admin_restaurants_index()
    {
     

        $response = $this->get(route('admin.restaurants.index'));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_restaurants_index()
    {
       

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.index'));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_restaurants_index()
    {

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
    

        $response = $this->get(route('admin.restaurants.create'));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_restaurants_create()
    {
      

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.create'));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_restaurants_create()
    {

        
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.restaurants.create'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_restaurants_store()
    {
       

        $response = $this->get(route('admin.restaurants.store'));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_restaurants_store()
    {
      

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.store'));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_restaurants_store()
    {
      
        
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.restaurants.store'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_store_restaurants(){

        $categoryIds = [];
        for($i=1; $i<=3; $i++){
            $category = Category::create([
                'name' => 'カテゴリ'. $i
            ]);
            array_push($categoryIds, $category->id);
        }

        $regular_holidays = RegularHoliday::factory()->count(3)->create();
        $regular_holiday_ids = $regular_holidays->pluck('id')->toArray();


        

            $restaurant = [
                'name' => 'テスト',
                'description' => 'テスト',
                'lowest_price' => 1000,
                'highest_price' => 5000,
                'postal_code' => '0000000',
                'address' => 'テスト',
                'opening_time' =>  '10:00',
                'closing_time' =>  '20:00',
                'regular_holiday_ids' => $regular_holiday_ids,
                'seating_capacity' => 50,
                'category_ids' => $categoryIds
            ];

            


            $response = $this->post(route('admin.restaurants.store'), $restaurant);

            unset($restaurant['category_ids'], $restaurant['regular_holiday_ids']);
            $this->assertDatabaseMissing('restaurants', $restaurant);

            foreach ($categoryIds as $categoryId) {
                $this->assertDatabaseMissing('category_restaurant', [
                    'category_id' => $categoryId,
                ]);
            }
            foreach ($regular_holiday_ids as $regular_holiday_id) {
                $this->assertDatabaseMissing('regular_holiday_restaurant', [
                    'regular_holiday_id' => $regular_holiday_id,
                ]);
    }
    }
    public function test_user_cannot_store_restaurants(){

        $user=User::factory()->create();
        $this->actingAs($user);

        $categoryIds = [];
        for($i=1; $i<=3; $i++){
            $category = Category::create([
                'name' => 'カテゴリ'. $i
            ]);
            array_push($categoryIds, $category->id);
        }

            $regular_holidays = RegularHoliday::factory()->count(3)->create();
            $regular_holiday_ids = $regular_holidays->pluck('id')->toArray();

        

            $restaurant = [
                'name' => 'テスト',
                'description' => 'テスト',
                'lowest_price' => 1000,
                'highest_price' => 5000,
                'postal_code' => '0000000',
                'address' => 'テスト',
                'opening_time' =>  '10:00',
                'closing_time' =>  '20:00',
                'regular_holiday_ids' => $regular_holiday_ids,
                'seating_capacity' => 50,
                'category_ids' => $categoryIds
            ];

            $response = $this->post(route('admin.restaurants.store'), $restaurant);

            unset($restaurant['category_ids'], $restaurant['regular_holiday_ids']);
            $this->assertDatabaseMissing('restaurants', $restaurant);

            foreach ($categoryIds as $categoryId) {
                $this->assertDatabaseMissing('category_restaurant', [
                    'category_id' => $categoryId,
                ]);
            }
                foreach ($regular_holiday_ids as $regular_holiday_id) {
                    $this->assertDatabaseMissing('regular_holiday_restaurant', [
                        'regular_holiday_id' => $regular_holiday_id,
                    ]);
        }
    }

    public function test_admin_can_store_restaurants(){

        $admin=Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $categoryIds = [];
        for($i=1; $i<=3; $i++){
            $category = Category::create([
                'name' => 'カテゴリ'. $i
            ]);
            array_push($categoryIds, $category->id);
        }

        $regular_holidays = RegularHoliday::factory()->count(3)->create();
        $regular_holiday_ids = $regular_holidays->pluck('id')->toArray();


        

            $restaurant = [
                'name' => 'テスト',
                'description' => 'テスト',
                'lowest_price' => 1000,
                'highest_price' => 5000,
                'postal_code' => '0000000',
                'address' => 'テスト',
                'opening_time' =>  '10:00',
                'closing_time' =>  '20:00',
                'regular_holiday_ids'=> $regular_holiday_ids,
                'seating_capacity' => 50,
                'category_ids' => $categoryIds
            ];

            $response = $this->post(route('admin.restaurants.store'), $restaurant);

            unset($restaurant['category_ids'], $restaurant['regular_holiday_ids']);
            $this->assertDatabaseHas('restaurants', $restaurant);

            foreach ($categoryIds as $categoryId) {
                $this->assertDatabaseHas('category_restaurant', [
                    'category_id' => $categoryId,
                ]);
        }
        foreach ($regular_holiday_ids as $regular_holiday_id) {
            $this->assertDatabaseHas('regular_holiday_restaurant', [
                'regular_holiday_id' => $regular_holiday_id,
            ]);
}
    }

    public function test_guest_cannot_access_admin_restaurants_edit()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.edit', $restaurant));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_restaurants_edit()
    {
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.edit', $restaurant));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_restaurants_edit()
    {
        $restaurant = Restaurant::factory()->create();
        
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.restaurants.edit', $restaurant));

        $response->assertStatus(200);
    }

    

    public function test_guest_cannot_access_admin_restaurants_update()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.update', $restaurant));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_restaurants_update()
    {
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.update', $restaurant));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_restaurants_update()
    {
        $restaurant = Restaurant::factory()->create();
        
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.restaurants.update', $restaurant));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_update_restaurants(){

        $old_restaurant = Restaurant::factory()->create();

        $categoryIds = [];
        for($i=1; $i<=3; $i++){
            $category = Category::create([
                'name' => 'カテゴリ'. $i
            ]);
            array_push($categoryIds, $category->id);

        }
        $regular_holidays = RegularHoliday::factory()->count(3)->create();
        $regular_holiday_ids = $regular_holidays->pluck('id')->toArray();


            $restaurant = [
                'name' => 'テスト',
                'description' => 'テスト',
                'lowest_price' => 1000,
                'highest_price' => 5000,
                'postal_code' => '0000000',
                'address' => 'テスト',
                'opening_time' =>  '10:00',
                'closing_time' =>  '20:00',
                'regular_holiday_ids' => $regular_holiday_ids,
                'seating_capacity' => 100,
                'category_ids' => $categoryIds
            ];

            $response = $this->patch(route('admin.restaurants.update', $old_restaurant), $restaurant);

            unset($restaurant['category_ids'], $restaurant['regular_holiday_ids']);
            $this->assertDatabaseMissing('restaurants', $restaurant);

            foreach ($categoryIds as $categoryId) {
                $this->assertDatabaseMissing('category_restaurant', [
                    'category_id' => $categoryId,
                ]);
        }
        foreach ($regular_holiday_ids as $regular_holiday_id) {
            $this->assertDatabaseMissing('regular_holiday_restaurant', [
                'regular_holiday_id' => $regular_holiday_id,
            ]);
}
    }

    public function test_user_cannot_update_restaurants(){

        $old_restaurant = Restaurant::factory()->create();
        $user=User::factory()->create();
       

        $categoryIds = [];
        for($i=1; $i<=3; $i++){
            $category = Category::create([
                'name' => 'カテゴリ'. $i
            ]);
            array_push($categoryIds, $category->id);

        }
        $regular_holidays = RegularHoliday::factory()->count(3)->create();
        $regular_holiday_ids = $regular_holidays->pluck('id')->toArray();


            $restaurant = [
                'name' => 'テスト',
                'description' => 'テスト',
                'lowest_price' => 1000,
                'highest_price' => 5000,
                'postal_code' => '0000000',
                'address' => 'テスト',
                'opening_time' =>  '10:00',
                'closing_time' =>  '20:00',
                'regular_holiday_ids' => $regular_holiday_ids,
                'seating_capacity' => 100,
                'category_ids' => $categoryIds
            ];

            $response = $this->actingAs($user)->patch(route('admin.restaurants.update', $old_restaurant), $restaurant);

            unset($restaurant['category_ids'], $restaurant['regular_holiday_ids']);
            $this->assertDatabaseMissing('restaurants', $restaurant);

            foreach ($categoryIds as $categoryId) {
                $this->assertDatabaseMissing('category_restaurant', [
                    'category_id' => $categoryId,
                ]);
        }
        foreach ($regular_holiday_ids as $regular_holiday_id) {
            $this->assertDatabaseMissing('regular_holiday_restaurant', [
                'regular_holiday_id' => $regular_holiday_id,
            ]);
}
        
    }

    public function test_admin_can_update_restaurants(){

        $old_restaurant = Restaurant::factory()->create();

        $admin=Admin::factory()->create();

        $categoryIds = [];
        for($i=1; $i<=3; $i++){
            $category = Category::create([
                'name' => 'カテゴリ'. $i
            ]);
            array_push($categoryIds, $category->id);

        }
        $regular_holidays = RegularHoliday::factory()->count(3)->create();
        $regular_holiday_ids = $regular_holidays->pluck('id')->toArray();


            $restaurant = [
                'name' => 'テスト',
                'description' => 'テスト',
                'lowest_price' => 1000,
                'highest_price' => 5000,
                'postal_code' => '0000000',
                'address' => 'テスト',
                'opening_time' =>  '10:00',
                'closing_time' =>  '20:00',
                'regular_holiday_ids' => $regular_holiday_ids,
                'seating_capacity' => 50,
                'category_ids' => $categoryIds
            ];

            $response = $this->actingAs($admin, 'admin')->patch(route('admin.restaurants.update', $old_restaurant), $restaurant);

            unset($restaurant['category_ids'], $restaurant['regular_holiday_ids']);
            $this->assertDatabaseHas('restaurants', $restaurant);

            foreach ($categoryIds as $categoryId) {
                $this->assertDatabaseHas('category_restaurant', [
                    'category_id' => $categoryId,
                ]);
        }        foreach ($regular_holiday_ids as $regular_holiday_id) {
            $this->assertDatabaseHas('regular_holiday_restaurant', [
                'regular_holiday_id' => $regular_holiday_id,
            ]);
}
    }

    public function test_guest_cannot_access_admin_restaurants_destroy()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.destroy', $restaurant));

        $response->assertRedirect(route('admin.login'));
    }

  
    public function test_user_cannot_access_admin_restaurants_destroy()
    {
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.destroy', $restaurant));

        $response->assertRedirect(route('admin.login'));
    }

   
    public function test_adminUser_can_access_admin_restaurants_destroy()
    {
        $restaurant = Restaurant::factory()->create();
        
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.restaurants.destroy', $restaurant));

        $response->assertStatus(200);
    }
    }