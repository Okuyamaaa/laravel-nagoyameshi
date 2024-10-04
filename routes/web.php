<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
// use App\Http\Controllers\Admin\UserController;
// use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SubscriptionController;
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
    Route::resource('company', CompanyController::class);
    Route::resource('terms', TermController::class);
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