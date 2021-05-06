<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'customer'], function() {
    Route::get('auth/register', [\App\Http\Controllers\CustomerAuth\RegisterController::class, 'showRegistrationForm']);
    Route::get('auth/login', [\App\Http\Controllers\CustomerAuth\LoginController::class, 'showLoginForm'])->name('member.login');
    Route::post('auth/register', [\App\Http\Controllers\CustomerAuth\RegisterController::class, 'create']);
    Route::patch('auth/register', [\App\Http\Controllers\CustomerAuth\RegisterController::class, 'CheckExistingEmail']);
    Route::post('auth/login', [\App\Http\Controllers\CustomerAuth\LoginController::class, 'login']);
    Route::post('auth/logout', [\App\Http\Controllers\CustomerAuth\LoginController::class, 'logout'])->name('customer.auth.logout');

    Route::group(['middleware' => 'auth:customer'], function() {
        Route::post('datatable/dashboardmydevices', [\App\Http\Controllers\Customer\DatatableController::class, 'DashboardMyDevices']);
        Route::post('datatable/customermydevices', [\App\Http\Controllers\Customer\DatatableController::class, 'MyDevices']);
        Route::post('datatable/dashboardmybundles', [\App\Http\Controllers\Customer\DatatableController::class, 'DashboardMyBundles']);
        Route::post('datatable/customermybundles', [\App\Http\Controllers\Customer\DatatableController::class, 'MyBundles']);
        Route::get('dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index']);
        Route::post('getdevices', [\App\Http\Controllers\Customer\DashboardController::class, 'getdevices']);
        Route::post('getorders', [\App\Http\Controllers\Customer\DashboardController::class, 'getorder']);

        Route::get('my-devices', [\App\Http\Controllers\Customer\DeviceController::class, 'index']);
        Route::get('my-devices/{hashedId}', [\App\Http\Controllers\Customer\DeviceController::class, 'edit']);

        Route::get('my-bundles', [\App\Http\Controllers\Customer\BundleController::class, 'index']);
        Route::get('my-bundles/{hashedId}', [\App\Http\Controllers\Customer\BundleController::class, 'edit']);
        Route::get('my-bundles/{hashedId}/generatePDF', [\App\Http\Controllers\Admin\OrderController::class, 'generatePDF']);

        Route::get('profile', [\App\Http\Controllers\Customer\ProfileController::class, 'index']);

        Route::get('verification', [\App\Http\Controllers\Customer\ProfileController::class, 'verification']);
    });

});
Route::group(['prefix' => 'api', 'middleware' => 'auth:customer'], function() {
    Route::group(['prefix' => 'customer'], function() {
        Route::patch('profile/changepassword', [\App\Http\Controllers\Customer\ApiController::class, 'ChangePassword']);
        Route::get('orders/{hashedId}/orderItem', [\App\Http\Controllers\Customer\ApiController::class, 'GetOrderItem']);
        Route::patch('orders/{hashedid}/orderItem', [\App\Http\Controllers\Api\ApiController::class, 'PatchProduct']);
        Route::delete('bundle/{hashedId}/orderItem', [\App\Http\Controllers\Api\ApiController::class, 'DeleteOrderItem']);
        
        Route::post('verification', [\App\Http\Controllers\Customer\ApiController::class, 'Verification']);
        Route::patch('verification/resend', [\App\Http\Controllers\Customer\ApiController::class, 'ResendVerification']);
    });
});