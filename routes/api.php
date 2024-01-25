<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\NotificationsController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\SocialController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\UserController;
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
// Login

Route::post('login', [AuthController::class, "login"]);
// Signup
Route::post('signup', [AuthController::class, "signup"]);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/login/google', [SocialController::class, 'loginWithGoogle']);
Route::post('/login/apple', [SocialController::class, 'loginWithApple']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/otp', [OtpController::class, 'requestOtp']);
Route::post('verifyotp', [OtpController::class, 'verifyOtp']);
Route::post('/mobileotp', [OtpController::class, 'sendMobileOtp']);
Route::post('/verifymobileotp', [OtpController::class, 'verifyMobileOtp']);
Route::post('/add-address', [AddressController::class, 'addAddress']);
Route::get('/get-address', [AddressController::class, 'getAddress']);
Route::put('update-address', [AddressController::class, 'updateAddress']);
Route::delete('delete-address', [AddressController::class, 'deleteAddress']);


Route::group(
    ['middleware' => ['auth:sanctum']],
    function () {
        Route::get('account-info', [UserController::class, 'getAccountInfo']);
        Route::put('account-info', [UserController::class, 'updateAccountInfo']);
        Route::delete('account', [UserController::class, 'deleteAccount']);
        Route::put('change-password', [
            AuthController::class, 'updatePassword'
        ]);


        Route::post('/store-rating', [StoreController::class, 'storeRating']);
        // Route::post('/createCart', [CartController::class, 'createCart']);
        // Route::post('/addToCart', [CartController::class, 'addToCart']);
        // Route::post('/checkout', [CartController::class, 'checkout']);

    }

);

Route::get('/homescreen', [StoreController::class, 'homeScreen']);
Route::get('stores', [StoreController::class, 'getStores']);
Route::post('order/address', [AddressController::class, 'orderAddress']);
Route::get('search', [StoreController::class, 'SearchStores']);
Route::get('/storeById', [StoreController::class, 'show']);
Route::put('update-cart-items', [CartController::class, 'updateCartItem']);
Route::get('/filter', [StoreController::class, 'storeFilters']);
Route::post('/checkout', [CartController::class, 'checkout']);


Route::post('/createCart', [CartController::class, 'createCart']);
Route::post('/addToCart', [CartController::class, 'addToCart']);
// Route::put('/cart/{cart_item_id}', [CartController::class, 'updateCartItem']);
Route::put('/changeCartItemQuantity', [CartController::class, 'changeCartItemQuantity']);
Route::put('/changeCartItemServices', [CartController::class, 'changeCartItemServices']);
Route::delete('/deleteCartItem', [CartController::class, 'deleteCartItem']);
Route::delete('/deleteCartItems', [CartController::class, 'deleteCartItems']);
Route::get('/getCartItems', [CartController::class, 'getCartItems']);
Route::get('/pending-orders', [OrderController::class, 'getPendingOrders']);
Route::get('/orders-history', [OrderController::class, 'orderHistory']);
Route::post('/cancel-order', [OrderController::class, 'cancelOrder']);
Route::post('/order-invoice', [OrderController::class, 'sendInvoiceEmail']);
Route::get('/order-tracking', [OrderController::class, 'orderTracking']);

Route::get('/getCartTotalPrice', [CartController::class, 'getCartTotalPrice']);
Route::get('/getUserOrders', [CartController::class, 'getUserOrders']);
Route::get('/getOrderItems/{orderId}', [CartController::class, 'getOrderItems']);

// routes/web.php
// routes/api.php
Route::post('/send-notification', [NotificationsController::class, 'sendNotification']);
