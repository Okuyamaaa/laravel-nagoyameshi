<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Term;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TermTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_guest_cannot_access_index()
    {
        $response = $this->get(route('admin.terms.index'));

        $response->assertRedirect(route('admin.login'));


    }
    public function test_user_cannot_access_index()
    {

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.terms.index'));

        $response->assertRedirect(route('admin.login'));


    }
    public function test_admin_can_access_index()
    {
        $term =term::factory()->create();
        $adminUser = Admin::factory()->create();
        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.terms.index'));

        $response->assertStatus(200);


    }


    public function test_guest_cannot_access_edit()
    {
        $term=Term::factory()->create();
        $response = $this->get(route('admin.terms.edit', $term));

        $response->assertRedirect(route('admin.login'));


    }
    public function test_user_cannot_access_edit()
    {
        $term=Term::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.terms.edit', $term));

        $response->assertRedirect(route('admin.login'));


    }
    public function test_admin_can_access_edit()
    {
        $term=Term::factory()->create();
        $adminUser = Admin::factory()->create();
        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.terms.edit', $term));

        $response->assertStatus(200);


    }
    
    public function test_guest_cannot_update(){
        $old_term = Term::factory()->create();

        $term = ([
                'content'=>'test',
            
        ]);

        $response = $this->patch(route('admin.terms.update', $old_term), $term);

        $this->assertDatabaseMissing('terms', $term);
    }
    public function test_user_cannot_update(){
        $old_term = Term::factory()->create();
        $user=User::factory()->create();
        $term = ([
                'content'=>'test',
        
        ]);

        $response = $this->actingAs($user)->patch(route('admin.terms.update', $old_term), $term);

        $this->assertDatabaseMissing('terms', $term);
    }

    public function test_admin_can_update(){
        $old_term = Term::factory()->create();
        $adminUser=Admin::factory()->create();
        $term = ([
                'content'=>'test',
        
        ]);

        $response = $this->actingAs($adminUser, 'admin')->patch(route('admin.terms.update', $old_term), $term);

        $this->assertDatabaseHas('terms', $term);
    }


}
