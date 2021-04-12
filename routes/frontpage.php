<?php

use Illuminate\Support\Facades\Route;

// http://localhost:8000/builder/pagecontent/2
// Route::any('/{uri}', ['uses' => 'App\Http\Controllers\FrontPageController@handleRequest')->where('uri', '.*');
Route::get('/{uri}', [App\Http\Controllers\FrontPageController::class, 'handleRequest']);
// uilder/pagecontent/2
Route::get('/builder/pagecontent/{id}', ['as' => 'builder.pagecontent', 'uses' => 'App\Http\Controllers\FrontPageController@processRequest']);

Route::get('/api/web/getproductlist', ['as' => 'api.web.getproductlist', 'uses' => 'App\Http\Controllers\FrontPageController@getProductList']);

Route::post('/api/web/cart', ['as' => 'api.web.cart', 'uses' => 'App\Http\Controllers\FrontPageController@getCartList']);
Route::get('/cart/checkout', ['as' => 'cart.checkout', 'uses' => 'App\Http\Controllers\CartController@cartCheckout']);
// Route::get('/pagecontent/{id}', [App\Http\Controller::class, 'processRequest']);
Route::get('/', [\App\Http\Controllers\FrontPageController::class, 'landingPage']);
// Route::get('/about-us', [App\Http\Controllers\FrontPageController::class, 'aboutus']);
// Route::get('/how-it-works', [App\Http\Controllers\FrontPageController::class, 'howitworks']);


Route::group(['prefix' => 'products'], function() {
  Route::get('checkout', [App\Http\Controllers\CartController::class, 'checkout']);
  Route::post('checkout', [\App\Http\Controllers\CartController::class, 'storecheckout']);
  Route::resource('cart', App\Http\Controllers\CartController::class);
});
// Route::resource('my-cart', App\Http\Controllers\CartController::class);

Route::get('/products', [App\Http\Controllers\FrontPageController::class, 'products']);
Route::get('/products/sell', [App\Http\Controllers\FrontPageController::class, 'productsell']);
Route::get('/products/sell/{id}', [App\Http\Controllers\FrontPageController::class, 'productselldetails']);
Route::get('/products/{model}', [App\Http\Controllers\FrontPageController::class, 'productdetails']);
Route::post('/products/sell/payment-method', [App\Http\Controllers\FrontPageController::class, 'paymentmethod']);
Route::post('/products', [App\Http\Controllers\FrontPageController::class, 'productsearch']);

Route::get('products/category/{brand}', [App\Http\Controllers\DeviceController::class, 'checkout']);
Route::get('products/{brand}/{model}', [App\Http\Controllers\DeviceController::class, 'brandModel']);
Route::post('products/model', [App\Http\Controllers\DeviceController::class, 'model']);
Route::post('products/model/filter', [App\Http\Controllers\DeviceController::class, 'filterByStorageCondition']);
Route::post('products/network', [App\Http\Controllers\DeviceController::class, 'network']);
Route::resource('device', App\Http\Controllers\DeviceController::class);
Route::post('device/authStore', [App\Http\Controllers\DeviceController::class, 'store']);
Route::get('product/storegettings', [App\Http\Controllers\DeviceController::class, 'storeget']);
Route::get('order/{hashedId}/shippinglabel', [App\Http\Controllers\DeviceController::class, 'shippingLabelPDF']);

Route::get('paypal/success', [App\Http\Controllers\PaypalController::class, 'success'])->name('paypal.success');
Route::get('paypal/cancel', [App\Http\Controllers\PaypalController::class, 'cancel'])->name('paypal.cancel');

Route::get('test/sms', [App\Http\Controllers\FrontPageController::class, 'test']);
// Route::get('/{any}', [App\Http\Controllers\FrontPageController::class, 'custompage']);