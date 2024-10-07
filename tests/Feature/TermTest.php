<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Admin;
use App\Models\Term;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TermTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_guest_can_access_term(): void
    {
        $term = Term::factory()->create();

        $response = $this->get(route('terms.index'));

        $response->assertStatus(200);
    }

    public function test_user_can_access_term(){

        $term = Term::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('terms.index'));

        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_term(){

        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('terms.index'));

        $response->assertRedirect('admin/home');
    }
}
