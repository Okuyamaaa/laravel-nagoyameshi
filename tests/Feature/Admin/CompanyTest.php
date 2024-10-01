<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_guest_cannot_access_index()
    {
        $response = $this->get(route('admin.company.index'));

        $response->assertRedirect(route('admin.login'));


    }
    public function test_user_cannot_access_index()
    {

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.company.index'));

        $response->assertRedirect(route('admin.login'));


    }
    public function test_admin_can_access_index()
    {
        $company = Company::factory()->create();
        $adminUser = Admin::factory()->create();
        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.company.index'));

        $response->assertStatus(200);


    }


    public function test_guest_cannot_access_edit()
    {
        $company = Company::factory()->create();
        $response = $this->get(route('admin.company.edit', $company));

        $response->assertRedirect(route('admin.login'));


    }
    public function test_user_cannot_access_edit()
    {
        $company = Company::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.company.edit', $company));

        $response->assertRedirect(route('admin.login'));


    }
    public function test_admin_can_access_edit()
    {
        $company = Company::factory()->create();
        $adminUser = Admin::factory()->create();
        $response = $this->actingAs($adminUser, 'admin')->get(route('admin.company.edit', $company));

        $response->assertStatus(200);


    }

    public function test_guest_cannot_update(){
        $old_company = Company::factory()->create();

        $company = ([
                'name'=>'test',
                'postal_code'=>'1111111',
                'address'=>'test',
                'representative'=>'test',
                'establishment_date'=>'test',
                'capital'=>'test',
                'business'=>'test',
                'number_of_employees'=>'test',
        
        ]);

        $response = $this->patch(route('admin.company.update', $old_company), $company);

       $this->assertDatabaseMissing('companies', $company);
    }
    public function test_user_cannot_update(){
        $old_company = Company::factory()->create();
        $user=User::factory()->create();
        $company = ([
                'name'=>'test',
                'postal_code'=>'1111111',
                'address'=>'test',
                'representative'=>'test',
                'establishment_date'=>'test',
                'capital'=>'test',
                'business'=>'test',
                'number_of_employees'=>'test',
        
        ]);

        $response = $this->actingAs($user)->patch(route('admin.company.update', $old_company), $company);

        $this->assertDatabaseMissing('companies', $company);
    }

    public function test_admin_can_update(){
        $old_company = Company::factory()->create();
        $adminUser=Admin::factory()->create();
        $company = ([
            'name'=>'test',
            'postal_code'=>'0000000',
            'address'=>'test',
            'representative'=>'test',
            'establishment_date'=>'test',
            'capital'=>'test',
            'business'=>'test',
            'number_of_employees'=>'test',
        
        ]);

        $response = $this->actingAs($adminUser, 'admin')->patch(route('admin.company.update', $old_company), $company);

        $this->assertDatabaseHas('companies', $company);
    }

}
