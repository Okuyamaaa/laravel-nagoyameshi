<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Restaurant $restaurant, Review $review){

        $user = Auth::user();

        if($user->subscribed('premium_plan')){
            $reviews = Review::where('restaurant_id', $restaurant->id)->orderBy('created_at', 'desc')->paginate(5);
        }else{
            $reviews = Review::where('restaurant_id', $restaurant->id)->orderBy('created_at', 'desc')->paginate(3);
        }
        
        return view('reviews.index', compact('restaurant', 'reviews'));
    }

    public function create(Restaurant $restaurant){
        
        return view('reviews.create', compact('restaurant'));
    }

    public function store(Request $request, Restaurant $restaurant){
        $request->validate([
            'score'=> 'required|numeric|min:1|max:5',
            'content' => 'required',
        ]);

        $review = new Review();

        $review->content = $request->input('content');
        $review->score = $request->input('score');
        $review->restaurant_id = $restaurant->id;
        $review->user_id = Auth::id();

        $review->save();

        return to_route('restaurants.reviews.index', $restaurant)->with('flash_message', 'レビューを投稿しました。');


    }
    public function edit(Restaurant $restaurant, Review $review){
        $user_id = Auth::id();

        if($review->user_id !== $user_id){
            return to_route('restaurants.reviews.index', $restaurant)->with('error_message', '不正なアクセスです。');
        }
            return view('reviews.edit', compact('restaurant', 'review'));
       


    }

    public function update(Request $request, Restaurant $restaurant, Review $review){

        $request->validate([
            'score'=> 'required|numeric|min:1|max:5',
            'content' => 'required',
        ]);

        $user_id = Auth::id();

        if($review->user_id !== $user_id){
            return to_route('restaurants.reviews.index')->with('error_message', '不正なアクセスです。');
        }


            $review->content = $request->input('content');
            $review->score = $request->input('score');
            $review->restaurant_id = $restaurant->id;
            $review->user_id = Auth::id();
    
            $review->update();
    
            return to_route('restaurants.reviews.index', $restaurant)->with('flash_message', 'レビューを編集しました。');
        

    }

    public function destroy(Restaurant $restaurant, Review $review){
        
       

        if($review->user_id !== Auth::id()){
            return to_route('restaurants.reviews.index', $restaurant)->with('error_message', '不正なアクセスです。');
        }else{

            $review->delete();
            return to_route('restaurants.reviews.index', $restaurant)->with('flash_message', 'レビューを削除しました。');
        }
    
}
}