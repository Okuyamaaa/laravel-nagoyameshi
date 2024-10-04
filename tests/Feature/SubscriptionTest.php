<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class SubscriptionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_guest_cannot_access_create(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('subscription.create'));

        $response->assertRedirect('login');
    }

    public function test_notsubscribed_user_can_access_create(){

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('subscription.create'));

        $response->assertStatus(200);
    
    }

    public function test_subscribed_user_cannot_access_create(){
        $user = User::factory()->create();

        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

        $response = $this->actingAs($user)->get(route('subscription.create'));

        $response->assertRedirect('subscription.edit');
}
    public function test_admin_cannot_access_create(){
        $user = User::factory()->create();

        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($adminUser, 'admin')->get(route('subscription.create'));

        $response->assertRedirect('admin/home');



    }
    public function test_guest_cannot_store(){

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        
        $user = User::factory()->create();

        $response = $this->post(route('subscription.store'), $request_parameter);

        $response->assertRedirect('login');

    }
    public function test_not_subscribed_user_can_store(){

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        

        $user = User::factory()->create();

        $response = $this->actingAS($user)->post(route('subscription.store'), $request_parameter);

        // $response->assertStatus(302);  // リダイレクトが行われているか
        // $response->assertSessionHas('flash_message', '有料プランの登録が完了しました。');
    
        // サブスクリプションが作成されていることを確認
        $this->assertTrue($user->fresh()->subscribed('premium_plan'));


        // $this->assertTrue($user->subscribed('premium_plan'));

       
    }

    public function test_subscribed_user_cannot_store(){

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        

        $user = User::factory()->create();

        $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

        $response = $this->actingAS($user)->post(route('subscription.store'), $request_parameter);

        $response->assertRedirect('subscription.edit');



}    public function test_admin_cannot_store(){

    $request_parameter = [
        'paymentMethodId' => 'pm_card_visa'
    ];
    

    $user = User::factory()->create();

    $adminUser = Admin::factory()->create();

    $response = $this->actingAS($adminUser, 'admin')->post(route('subscription.store'), $request_parameter);

    $response->assertRedirect('admin/home');
}


public function test_guest_cannot_access_edit(): void
{
    $user = User::factory()->create();

    $response = $this->get(route('subscription.edit'));

    $response->assertRedirect('login');
}

public function test_notsubscribed_user_cannot_access_edit(){

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('subscription.edit'));

    $response->assertRedirect('subscription.create');

}

public function test_subscribed_user_cannot_access_edit(){
    $user = User::factory()->create();

    $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

    $response = $this->actingAs($user)->get(route('subscription.edit'));

    $response->assertStatus(200);
}
public function test_admin_cannot_access_edit(){
    $user = User::factory()->create();

    $adminUser = Admin::factory()->create();

    $response = $this->actingAs($adminUser, 'admin')->get(route('subscription.edit'));

    $response->assertRedirect('admin/home');


}
public function test_guest_cannot_update(){

    $request_parameter = [
        'paymentMethodId' => 'pm_card_visa'
    ];
    
    $user = User::factory()->create();

    $response = $this->patch(route('subscription.update'), $request_parameter);

    $response->assertRedirect('login');

}
public function test_not_subscribed_user_cannot_update(){

    $request_parameter = [
        'paymentMethodId' => 'pm_card_visa'
    ];
    

    $user = User::factory()->create();

    $response = $this->actingAS($user)->patch(route('subscription.update'), $request_parameter);

    $response->assertRedirect(('subscription.create'));

   
}

public function test_subscribed_user_can_update(){

    $request_parameter = [
        'paymentMethodId' => 'pm_card_visa'
    ];
    

    $user = User::factory()->create();

    $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

    $default_payment_method_id = $user->defaultPaymentMethod()->id;


    $response = $this->actingAS($user)->patch(route('subscription.update'), $request_parameter);

    $this->assertNotEquals($default_payment_method_id, 'pm_card_visa');



}    
public function test_admin_cannot_update(){

$request_parameter = [
    'paymentMethodId' => 'pm_card_visa'
];


$user = User::factory()->create();

$adminUser = Admin::factory()->create();

$response = $this->actingAS($adminUser, 'admin')->patch(route('subscription.update'), $request_parameter);

$response->assertRedirect('admin/home');
}

public function test_guest_cannot_access_cancel(): void
{
    $user = User::factory()->create();

    $response = $this->get(route('subscription.cancel'));

    $response->assertRedirect('login');
}

public function test_notsubscribed_user_cannot_access_cancel(){

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('subscription.cancel'));

    $response->assertRedirect('subscription.create');

}

public function test_subscribed_user_cannot_access_calcel(){
    $user = User::factory()->create();

    $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

    $response = $this->actingAs($user)->get(route('subscription.cancel'));

    $response->assertStatus(200);
}
public function test_admin_cannot_access_cancel(){
    $user = User::factory()->create();

    $adminUser = Admin::factory()->create();

    $response = $this->actingAs($adminUser, 'admin')->get(route('subscription.cancel'));

    $response->assertRedirect('admin/home');


}

public function test_guest_cannot_destroy(){

    $request_parameter = [
        'paymentMethodId' => 'pm_card_visa'
    ];
    
    $user = User::factory()->create();

    $response = $this->delete(route('subscription.destroy'), $request_parameter);

    $response->assertRedirect('login');

}
public function test_not_subscribed_user_cannot_destory(){

    $request_parameter = [
        'paymentMethodId' => 'pm_card_visa'
    ];
    

    $user = User::factory()->create();

    $response = $this->actingAS($user)->delete(route('subscription.destroy'), $request_parameter);

    $response->assertRedirect(('subscription.create'));

   
}

public function test_subscribed_user_can_destroy(){

    $request_parameter = [
        'paymentMethodId' => 'pm_card_visa'
    ];
    

    $user = User::factory()->create();

    $user->newSubscription('premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm')->create('pm_card_visa');

    $default_payment_method_id = $user->defaultPaymentMethod()->id;


    $response = $this->actingAS($user)->delete(route('subscription.destroy'), $request_parameter);

    $this->assertFalse($user->subscribed('premium_plan'));



}    
public function test_admin_cannot_destroy(){

$request_parameter = [
    'paymentMethodId' => 'pm_card_visa'
];


$user = User::factory()->create();

$adminUser = Admin::factory()->create();

$response = $this->actingAS($adminUser, 'admin')->delete(route('subscription.destroy'), $request_parameter);

$response->assertRedirect('admin/home');
}
}
