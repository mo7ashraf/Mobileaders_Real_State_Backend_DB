<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ListingWebController;
use App\Http\Controllers\Web\SellerWebController;
use App\Http\Controllers\Web\SearchWebController;
use App\Http\Controllers\Web\PageWebController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\ProfileWebController;
use App\Http\Controllers\Web\AdsWebController;

Route::get('/',                 [HomeController::class, 'index'])->name('web.home');
Route::get('/search',           [SearchWebController::class, 'index'])->name('web.search');
Route::get('/listings/{id}',    [ListingWebController::class, 'show'])->name('web.listing.show');
Route::get('/sellers/{id}',     [SellerWebController::class, 'show'])->name('web.seller.show');

Route::get('/policies',         [PageWebController::class, 'policiesIndex'])->name('web.policies.index');
Route::get('/policies/{slug}',  [PageWebController::class, 'policy'])->name('web.policies.show');

Route::get('/support',          [PageWebController::class, 'support'])->name('web.support');

// Simple health page
Route::get('/ping', fn() => view('web.ping'));

// Auth (web session)
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthWebController::class, 'showLogin'])->name('web.login');
    Route::post('/login',   [AuthWebController::class, 'login'])->name('web.login.post');
    Route::get('/register', [AuthWebController::class, 'showRegister'])->name('web.register');
    Route::post('/register',[AuthWebController::class, 'register'])->name('web.register.post');
});
Route::post('/logout', [AuthWebController::class, 'logout'])->middleware('auth')->name('web.logout');

// Account + Ads
Route::middleware('auth')->group(function () {
    Route::get('/account',        [ProfileWebController::class, 'show'])->name('web.account');
    Route::post('/account',       [ProfileWebController::class, 'update'])->name('web.account.update');

    Route::get('/ads/new',        [AdsWebController::class, 'create'])->name('web.ads.create');
    Route::post('/ads',           [AdsWebController::class, 'store'])->name('web.ads.store');
    Route::get('/account/listings',[AdsWebController::class, 'mine'])->name('web.ads.mine');
    Route::post('/ads/{id}/delete',[AdsWebController::class, 'destroy'])->name('web.ads.delete');
});
