<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'customer'], function() {
  Route::get('auth/register', [\App\Http\Controllers\CustomerAuth\RegisterController::class, 'showRegistrationForm']);
  Route::get('auth/login', [\App\Http\Controllers\CustomerAuth\LoginController::class, 'showLoginForm'])->name('member.login');
  Route::post('auth/register', [\App\Http\Controllers\CustomerAuth\RegisterController::class, 'create']);
  Route::post('auth/login', [\App\Http\Controllers\CustomerAuth\LoginController::class, 'login']);
  Route::post('auth/logout', [\App\Http\Controllers\CustomerAuth\LoginController::class, 'logout'])->name('customer.auth.logout');

  Route::group(['middleware' => 'auth:customer'], function() {
    Route::get('dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index']);
    Route::post('getdevices', [\App\Http\Controllers\Customer\DashboardController::class, 'getdevices']);
    Route::post('getorders', [\App\Http\Controllers\Customer\DashboardController::class, 'getorder']);
  });

});