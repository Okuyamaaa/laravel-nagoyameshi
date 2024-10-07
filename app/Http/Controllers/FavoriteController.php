<?php

namespace App\Http\Controllers;

use App\Models\user;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(){

        $favorite_restaurants = Auth::user()->favorite_restaurants()->orderBy('created_at', 'desc')->paginate(15);

        return view('favorites.index', compact('favorite_restaurants'));
    }

    public function store($restaurant_id){

        Auth::user()->favorite_restaurants()->attach($restaurant_id);

        return back()->with('flash_message', 'お気に入りに追加しました。');
    }

    public function destroy($restaurant_id){

        Auth::user()->favorite_restaurants()->detach($restaurant_id);

        return back()->with('flash_messdage', 'お気に入りに削除しました。');
    }
}
