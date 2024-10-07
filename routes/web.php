<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
// use App\Http\Controllers\Admin\UserController;
// use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\CategoryController;
// use App\Http\Controllers\Admin\CompanyController;
// use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TermController;
use App\Http\Middleware\Subscribed;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



require __DIR__.'/auth.php';

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('home', [Admin\HomeController::class, 'index'])->name('home');

    Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
});
    
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::resource('restaurants', Admin\RestaurantController::class);
    // Route::get('/admin/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
    // Route::get('/admin/restaurants/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');
    // Route::get('restaurants', [RestaurantController::class, 'create'])->name('restaurants.create');
    // Route::post('/admin/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
    // Route::get('/admin/restaurants/{restaurant}', [RestaurantController::class, 'edit'])->name('restaurants.edit');
    // Route::patch('restaurants', [RestaurantController::class, 'update'])->name('restaurants.update');
    // Route::delete('restaurants', [RestaurantController::class, 'destroy'])->name('restaurants.destroy');
    Route::resource('categories', CategoryController::class);
    Route::resource('company', Admin\CompanyController::class);
    Route::resource('terms', Admin\TermController::class);
});

Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('home',[HomeController::class, 'index'])->name('/home');
    Route::resource('restaurants', RestaurantController::class);
    Route::group(['middleware' => ['auth', 'verified']], function () {
         Route::resource('user', UserController::class)->only(['index', 'edit', 'update']);
    // Route::group(['middleware' =>  'subscribed'], function () {
    //     Route::resource('subscription', SubscriptionController::class)->only(['edit', 'update', 'cancel', 'destroy']);

    // });
    // Route::group(['middleware' =>  'not.subscribed'], function () {
    //     Route::resource('subscription', SubscriptionController::class)->only(['create', 'store']);
    // });
   });
});

// Route::middleware(['guest:admin', 'auth', 'not.subscribed'])->group(function(){
//     Route::resource('subscription', SubscriptionController::class)->only(['create', 'store']);
// });
// Route::middleware(['guest:admin', 'auth', 'subscribed'])->group(function(){
//     Route::resource('subscription', SubscriptionController::class)->only(['index', 'edit', 'update']);
// });

Route::middleware(['guest:admin', 'auth', 'not.subscribed'])->group(function () {
    Route::get('subscription/create', [SubscriptionController::class, 'create'])->name('subscription.create');
    Route::post('subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
});

Route::middleware(['guest:admin', 'auth', 'subscribed'])->group(function () {
    Route::get('subscription/edit', [SubscriptionController::class, 'edit'])->name('subscription.edit');
    Route::patch('subscription', [SubscriptionController::class, 'update'])->name('subscription.update');
    Route::get('subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    Route::delete('subscription', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');
});

Route::middleware(['guest:admin', 'auth', 'verified'])->group(function () {
    Route::get('restaurants/{restaurant}/reviews', [ReviewController::class, 'index'])->name('restaurants.reviews.index');
});
Route::middleware(['guest:admin', 'auth', 'verified', 'subscribed'])->group(function () {

    //  Route::resource('restaurants.reviews', ReviewController::class)->only('create', 'store', 'edit', 'update', 'destroy');
    Route::get('restaurants/{restaurant}/reviews/create', [ReviewController::class, 'create'])->name('restaurants.reviews.create');
    Route::post('restaurants/{restaurant}/reviews', [ReviewController::class, 'store'])->name('restaurants.reviews.store');
    Route::get('restaurants/{restaurant}/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('restaurants.reviews.edit');
    Route::patch('restaurants/{restaurant}/reviews/{review}', [ReviewController::class, 'update'])->name('restaurants.reviews.update');
    Route::delete('restaurants/{restaurant}/reviews/{review}', [ReviewController::class, 'destroy'])->name('restaurants.reviews.destroy');
});

Route::middleware(['guest:admin', 'auth', 'verified', 'subscribed'])->group(function(){
    Route::get('reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('restaurants/{restaurant}/reservations/create', [ReservationController::class, 'create'])->name('restaurants.reservations.create');
    Route::post('restaurants/{restaurant}/reservations', [ReservationController::class, 'store'])->name('restaurants.reservations.store');
    Route::delete('reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
});

Route::middleware(['guest:admin', 'auth', 'verified', 'subscribed'])->group(function(){
    Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('favorites/{restaurant_id}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('favorites/{restaurant_id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

Route::middleware(['guest:admin'])->group(function(){
    Route::get('company', [CompanyController::class, 'index'])->name('company.index');
    Route::get('terms', [TermController::class, 'index'])->name('terms.index');
});