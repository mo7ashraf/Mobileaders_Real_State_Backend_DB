
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

Route::get('/', [PingController::class, 'ok']);

Route::get('/listings', [ListingController::class, 'index']);

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
