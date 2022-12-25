<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use App\Models\Appointment;
use App\Models\User;
use App\Models\DentistSchedule;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // if(Auth::check()){
    //     $user = Auth::user();
    //     return view('welcome')
    //         ->with('user', $user->only(['lname', 'fname', 'mname', 'suffix', 'role', 'remark', 'office_id']));
    // }
    return view('welcome');
});



Route::get('/welcome-page-load-all-products', [App\Http\Controllers\WelcomePageAllProductsController::class, 'getProducts']);
//this route api will get all the products and apply some algo for top product



Auth::routes([
    'login' => 'false'
]);

Route::get('/load-user', function(){
    if(Auth::check()){
        return Auth::user();
    }
});


Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/sign-up', [App\Http\Controllers\SignUpController::class, 'index']);
Route::post('/sign-up', [App\Http\Controllers\SignUpController::class, 'store']);


Route::get('/get-user/{id}', [App\Http\Controllers\OpenUserController::class, 'getUser']);
Route::get('/get-offices', [App\Http\Controllers\OpenOfficeController::class, 'getOffices']);



//ADDRESS
Route::get('/load-provinces', [App\Http\Controllers\AddressController::class, 'loadProvinces']);
Route::get('/load-cities', [App\Http\Controllers\AddressController::class, 'loadCities']);
Route::get('/load-barangays', [App\Http\Controllers\AddressController::class, 'loadBarangays']);


/*              ADMINSITRATOR          */

Route::resource('/admin-home', App\Http\Controllers\Administrator\AdminHomeController::class);

Route::resource('/users', App\Http\Controllers\Administrator\UserController::class);
Route::get('/get-users', [App\Http\Controllers\Administrator\UserController::class, 'getUsers']);
Route::get('/get-user-offices', [App\Http\Controllers\Administrator\UserController::class, 'getOffices']);
Route::post('/user-reset-password/{id}', [App\Http\Controllers\Administrator\UserController::class, 'resetPassword']);


/*             ADMINSITRATOR          */


Route::resource('/vendor/dashboard', App\Http\Controllers\Vendor\VendorDashboardController::class);

Route::resource('/vendor/my-store', App\Http\Controllers\Vendor\VendorMyStoreController::class);
Route::get('/vendor/get-my-stores', [App\Http\Controllers\Vendor\VendorMyStoreController::class, 'getMyStores']);
Route::get('/vendor/store-info/{id}', [App\Http\Controllers\Vendor\VendorStoreInfoController::class, 'index']);

Route::get('/vendor/my-products', [App\Http\Controllers\Vendor\VendorProductController::class, 'index']);
Route::post('/vendor/my-products', [App\Http\Controllers\Vendor\VendorProductController::class, 'store']);
Route::post('/vendor/my-products/{id}', [App\Http\Controllers\Vendor\VendorProductController::class, 'update']);
Route::get('/vendor/get-product-lists', [App\Http\Controllers\Vendor\VendorProductController::class, 'getProducts']);
Route::get('/vendor/my-products/{id}', [App\Http\Controllers\Vendor\VendorProductController::class, 'show']);
Route::delete('/vendor/my-products/{id}', [App\Http\Controllers\Vendor\VendorProductController::class, 'destroy']);


/*           ADMINSITRATOR          */



Route::get('/buy-now/{product_id}', [App\Http\Controllers\BuyNowController::class, 'index']);
Route::post('/buy-now-store', [App\Http\Controllers\BuyNowController::class, 'store']);




/*          USER          */
Route::get('/my-order', [App\Http\Controllers\User\MyOrderController::class, 'index']);
Route::get('/get-my-orders', [App\Http\Controllers\User\MyOrderController::class, 'getMyOrders']);
Route::post('/submit-product-rating', [App\Http\Controllers\ProductRatingController::class, 'store']);

Route::get('/my-cart', [App\Http\Controllers\User\CartController::class, 'index']);
Route::get('/get-cart-items', [App\Http\Controllers\User\CartController::class, 'getCartItems']);
Route::post('/submit-product-rating', [App\Http\Controllers\User\CartController::class, 'store']);




Route::get('/session', function(){
    return Session::all();
});





Route::get('/applogout', function(Request $req){
    \Auth::logout();
    $req->session()->invalidate();
    $req->session()->regenerateToken();


});
