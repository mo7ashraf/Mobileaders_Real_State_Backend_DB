
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PingController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AppSettingsController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\DB;


Route::get('/', [PingController::class, 'ok']);

Route::get('/listings', [ListingController::class, 'index']);
Route::get('/listings/trending', [ListingController::class, 'index']); // same handler

// Categories (for apps/web)
Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/sellers', [SellerController::class, 'list']); // new
Route::get('/sellers/{id}', [SellerController::class, 'header']);
Route::get('/sellers/{id}/listings', [SellerController::class, 'listings']);

Route::get('/account/profile', [AccountController::class, 'getProfile']);
Route::match(['post','patch'],'/account/profile', [AccountController::class, 'setProfile']);
Route::post('/account/avatar', [AccountController::class, 'setAvatar']);
Route::get('/account/channels', [AccountController::class, 'getChannels']);
Route::match(['post','patch'],'/account/channels', [AccountController::class, 'setChannels']);
Route::get('/account/links', [AccountController::class, 'getLinks']);
Route::match(['post','patch'],'/account/links', [AccountController::class, 'setLinks']);

Route::get('/app/settings', [AppSettingsController::class, 'get']);
Route::match(['post','patch'],'/app/settings', [AppSettingsController::class, 'set']);

Route::get('/support/settings', [SupportController::class, 'get']);
Route::match(['post','patch'],'/support/settings', [SupportController::class, 'set']);

Route::get('/policies/{slug}', [PolicyController::class, 'get']);


Route::get('/conversations', [ConversationsController::class, 'index']);
Route::post('/conversations',                [ConversationsController::class,'store']);
Route::get('/conversations/{id}/messages',   [ConversationsController::class,'messages']);
Route::post('/conversations/{id}/messages',  [ConversationsController::class,'send']);

Route::get('/notifications', [NotificationController::class, 'index']);
Route::post('/notifications/{id}/star', [NotificationController::class, 'star']);
Route::post('/notifications/read-all', [NotificationController::class, 'readAll']);

Route::post('/favorites/{listingId}', [FavoriteController::class, 'add']);
Route::delete('/favorites/{listingId}', [FavoriteController::class, 'remove']);
Route::get('/favorites', [FavoriteController::class, 'list']);

Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::post('/orders', [OrderController::class, 'create']);

Route::post('/requests', [RequestController::class, 'create']);

// Auth (email/password + OTP)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/otp/request', [OtpController::class, 'requestCode']);
    Route::post('/otp/verify', [OtpController::class, 'verifyCode']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
