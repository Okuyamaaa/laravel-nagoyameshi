<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_guest_cannot_access_index(): void
    {
        
        $user = User::factory()->create();

        $response = $this->get(route('user.index'));

        $response->assertRedirect('/login');
    }

    public function test_user_can_access_index(){
        $user = User::factory()->create();

        $response=$this->actingAs($user)->get(route('user.index'));

        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_index(){
        $adminUser = Admin::factory()->create();

        $user = User::factory()->create();

        $response=$this->actingAs($adminUser, 'admin')->get(route('user.index'));

        $response->assertRedirect('admin/home');
    }

    public function test_guest_cannot_access_edit(){
        $user = User::factory()->create();

        $response=$this->get(route('user.edit', ['user'=>$user->id]));

        $response->assertRedirect('/user');

    }

    public function test_cannot_others_user_access_edit(){
        $user = User::factory()->create();
        $new_user = User::factory()->create();

        if($user->id != $new_user->id){
            $response = $this->actingAs($new_user)->get(route('user.edit', ['user'=>$user->id]));
            $response->assertRedirect('/user');
        }
    }
    public function test_can_user_access_edit(){
        $user = User::factory()->create();
        

        
            $response = $this->actingAs($user)->get(route('user.edit', ['user'=>$user->id]));
            $response->assertStatus(200);
        
    }

    public function test_admin_cannot_access_edit(){
        $user = User::factory()->create();

        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('user.edit', ['user'=>$user->id]));

        $response->assertRedirect('admin/home');

    }

    public function test_guest_cannot_update(){
        
        $user = User::factory()->create();

        $new_user_info = User::factory()->create()->toArray();

        $response = $this->patch(route('user.update', $user), $new_user_info);

        $this->assertDatabaseMissing('users', $new_user_info);
    }

    public function test_others_user_cannot_update(){
        $user = User::factory()->create();

        $other_user = User::factory()->create();

        $new_user_info = User::factory()->create()->toArray();

        
            $response = $this->actingAs($other_user)->patch(route('user.update', $user), $new_user_info);
            $this->assertDatabaseMissing('users', $new_user_info);
  
    }
        public function test_user_can_update(){
            $user = User::factory()->create([
                'name' => 'テスト',
                'kana' => 'テスト',
                'email' => 'test@example.com',
                'postal_code' => '1001000',
                'address' => 'テスト',
                'phone_number' => '00000000000',

            ]);
    
            $new_user_info = [
                'name' => 'ユーザー',
                'kana' => 'ユーザー',
                'email' => 'user@example.com',
                'postal_code' => '2002000',
                'address' => 'ユーザー',
                'phone_number' => '22222222222',

            ];

            unset($new_user_info['id'], $new_user_info['created_at'], $new_user_info['updated_at'], $new_user_info['email_verified_at']);
    
            $response = $this->actingAs($user)->patch(route('user.update', $user), $new_user_info);
            $this->assertDatabaseHas('users', $new_user_info);
         


    }

    public function test_admin_cannot_update(){
        $user = User::factory()->create();

        $adminUser = Admin::factory()->create();

        $new_user_info = User::factory()->make()->toArray();

        unset($new_user_info['id'], $new_user_info['created_at'], $new_user_info['updated_at'], $new_user_info['email_verified_at']);

        $response = $this->actingAs($adminUser, 'admin')->patch(route('user.edit', $user), $new_user_info);

        $this->assertDatabaseMissing('users', $new_user_info);
    }

    
}
