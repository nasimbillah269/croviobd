<?php


use App\Http\Controllers\Api\ApiWelcomeController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::any('/slider',[ApiWelcomeController::class,'slider']);
Route::get('/home-categories',[ApiWelcomeController::class,'homeCategories']);
Route::get('/home-pages-menus',[ApiWelcomeController::class,'pagesMenus']);
Route::get('/page-view/{id}',[ApiWelcomeController::class,'pageView']);
Route::get('/home-products',[ApiWelcomeController::class,'homeProducts']);
Route::get('/may-you-like',[ApiWelcomeController::class,'mayYouLikeProducts']);
Route::get('/general-info',[ApiWelcomeController::class,'generalInfo']);

Route::get('/product/categories',[ApiWelcomeController::class,'categories']);
Route::get('/product/category/{slug}',[ApiWelcomeController::class,'categoryView']);
Route::get('/products',[ApiWelcomeController::class,'products']);
Route::get('/product/{slug}',[ApiWelcomeController::class,'productView']);
Route::get('/product-search',[ApiWelcomeController::class,'productSearch']);
Route::get('/subscribe',[ApiWelcomeController::class,'subscribe']);

Route::get('/page/{slug}',[ApiWelcomeController::class,'pageView']);


Route::post('/login',[ApiAuthController::class,'login']);
Route::post('/registration',[ApiAuthController::class,'registration']);
Route::post('/forget/password',[ApiAuthController::class,'forgetPassword']);
Route::post('/reset/password',[ApiAuthController::class,'resetPassword']);


Route::group(['middleware'=>'APIToken'], function(){
    
Route::post('/log-out',[ApiUserController::class,'logOut']);

Route::any('/add-to-cart/{id}',[ApiUserController::class,'addToCart']);
Route::any('/change-to-cart/{id}/{type}',[ApiUserController::class,'cartUpdate']);
Route::any('/carts-charge',[ApiUserController::class,'cartsCharge']);
Route::get('/carts',[ApiUserController::class,'carts']);
Route::any('/checkout',[ApiUserController::class,'checkOut']);
Route::get('/my-wishlist',[ApiUserController::class,'myWishlist']);
Route::get('/my-compare',[ApiUserController::class,'myCompare']);
Route::get('/wishlist-compare/update/{id}/{type}',[ApiUserController::class,'wishlistCompareUpdate']);

});


Route::group(['prefix'=>'user','middleware'=>'APIToken'], function(){
    
    Route::any('/profile',[ApiUserController::class,'profile']);
    Route::post('/change-password',[ApiUserController::class,'changePassword']);
    
    Route::get('/orders',[ApiUserController::class,'orders']);
    Route::get('/order-details/{id}',[ApiUserController::class,'orderDetails']);
    Route::post('/order-cancel/{id}',[ApiUserController::class,'orderCancel']);
    Route::post('/order-return/{id}',[ApiUserController::class,'orderReturn']);
    Route::get('/my-reviews',[ApiUserController::class,'myReviews']);
    Route::post('/order-review/{id}',[ApiUserController::class,'orderReview']);
    
});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
