<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
// use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;


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
    Route::resource('restaurants', RestaurantController::class);
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
    Route::group(['middleware' => ['auth', 'verified']], function () {
         Route::resource('user', UserController::class)->only(['index', 'edit', 'update']);
   });
});