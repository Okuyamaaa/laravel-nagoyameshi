<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_guest_can_access_home(): void
    {
        

        $response = $this->get(route('admin.home'));

        $response->assertRedirect('/login');
    }

    public function test_user_cannot_access_home(){


        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.home'));

        $response->assertRedirect('/home');
    }

    public function test_admin_can_access_home(){

        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.home'));

        $response->assertStatus(200);
    }
}
