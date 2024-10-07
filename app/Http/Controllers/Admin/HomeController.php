<?php

namespace App\Http\Controllers\Admin;

use App\Models\Reservation;
use App\Models\user;
use App\Models\restaurant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index() {

        $total_users = User::all()->count();

        $total_premium_users = DB::table('subscriptions')->where('stripe_status', '=', 'active')->count();

        $total_free_users = ($total_users - $total_premium_users);

        $total_restaurants = Restaurant::all()->count();

        $total_reservations = Reservation::all()->count();

        $sales_for_this_month = ($total_premium_users * 300);


        return view('admin.home', compact('total_users', 'total_premium_users', 'total_free_users', 'total_restaurants', 'total_reservations', 'sales_for_this_month'));
    }
}
