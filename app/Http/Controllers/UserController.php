<?php

namespace App\Http\Controllers;

// use App\Models\User;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Validation\Rule;
// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;


class UserController extends Controller
{
    public function index(){
        $user = Auth::user();

        return view('user.index', compact('user'));
    }

    public function edit(User $user){
        
        $user_id = Auth::id();
       

        if($user->id == $user_id){
            
        return view('user.edit', compact('user'));
        }else{
            return to_route('user.index')->with('error_message', '不正なアクセスです。');
        }
    }
    public function update(Request $request, User $user){
       
        $user_id = Auth::id();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'kana' => ['required', 'string', 'regex:/\A[ァ-ヴー\s]+\z/u', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'postal_code' => ['required', 'digits:7'],
            'address' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'digits_between:10, 11'],
            'birthday' => ['nullable', 'digits:8'],
            'occupation' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update([
            'name' => $request->name,
            'kana' => $request->kana,
            'email' => $request->email,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'birthday' => $request->birthday,
            'occupation' => $request->occupation,
        ]);


        

        if($user->id == $user_id){

        return to_route('user.index')->with('flash_message', '会員情報を編集しました。');
        }else{
            return to_route('user.index')->with('error_message', '不正なアクセスです。');
        }
    }
}
