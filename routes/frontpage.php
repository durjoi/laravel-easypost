<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\FrontPageController::class, 'welcome']);
Route::get('/about-us', [App\Http\Controllers\FrontPageController::class, 'aboutus']);
Route::get('/how-it-works', [App\Http\Controllers\FrontPageController::class, 'howitworks']);
Route::get('/contact-us', [App\Http\Controllers\FrontPageController::class, 'contactus']);

Route::group(['prefix' => 'products'], function() {
  Route::get('checkout', [App\Http\Controllers\CartController::class, 'checkout']);
  Route::post('checkout', [\App\Http\Controllers\CartController::class, 'storecheckout']);
  Route::resource('cart', App\Http\Controllers\CartController::class);
});

Route::get('/products', [App\Http\Controllers\FrontPageController::class, 'products']);
Route::get('/products/sell', [App\Http\Controllers\FrontPageController::class, 'productsell']);
Route::get('/products/sell/{id}', [App\Http\Controllers\FrontPageController::class, 'productselldetails']);
Route::get('/products/{model}', [App\Http\Controllers\FrontPageController::class, 'productdetails']);
Route::post('/products/sell/payment-method', [App\Http\Controllers\FrontPageController::class, 'paymentmethod']);
Route::post('/products', [App\Http\Controllers\FrontPageController::class, 'productsearch']);

Route::get('products/category/{brand}', [App\Http\Controllers\DeviceController::class, 'checkout']);
Route::post('products/model', [App\Http\Controllers\DeviceController::class, 'model']);
Route::post('products/network', [App\Http\Controllers\DeviceController::class, 'network']);
Route::resource('device', App\Http\Controllers\DeviceController::class);

Route::get('paypal/success', [App\Http\Controllers\PaypalController::class, 'success'])->name('paypal.success');
Route::get('paypal/cancel', [App\Http\Controllers\PaypalController::class, 'cancel'])->name('paypal.cancel');

Route::get('/{any}', [App\Http\Controllers\FrontPageController::class, 'custompage']);