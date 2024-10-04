<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{


   public function create(){

    $user = Auth::user();

if($user->subscribed('premium_plan')){
    return to_route('subscription.edit');
}
    $intent = Auth::user()->createSetupIntent();

    return view('subscription.create', compact('intent'));
   }

   public function store(Request $request){
   
    $request->user()->newSubscription(
        'premium_plan', 'price_1Q5nFM0497JNVZVLSA9Ofurm'
    )->create($request->paymentMethodId);
 return to_route('user.index')->with('flash_message', '有料プランの登録が完了しました。');
   }

   public function edit(){
    $user = Auth::user();
    $intent = Auth::user()->createSetupIntent();
    
    return view('subscription.edit', compact('user', 'intent'));

   }

   public function update(Request $request){

        $request->user()->updateDefaultPaymentMethod($request->paymentMethodId);

        return to_route('user.index')->with('flash_message', 'お支払方法を変更しました。');
   }

   public function cancel(){
    return view('subscription.cancel');
   }
   public function destroy(Request $request){

    
    $request->user()->subscription('premium_plan')->cancelNow();
      
   return to_route('user.index')->with('flash_message', '有料プランを解約しました。');
   }
}
