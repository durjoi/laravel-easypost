<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['prefix' => 'admin', 'middleware' => ['auth:web']], function() {
  Route::group(['prefix' => 'settings'], function() {
    Route::get('pages/section/{id}', [\App\Http\Controllers\Admin\PageController::class, 'pagesection']);
    Route::get('pages/content/{id}', [\App\Http\Controllers\Admin\PageController::class, 'pagecontent']);
    Route::get('pages/image/{id}', [\App\Http\Controllers\Admin\PageController::class, 'pageimage']);
    Route::get('pages/sectionimage/{id}', [\App\Http\Controllers\Admin\PageController::class, 'pagesectionimage']);
    Route::post('pages/manage', [\App\Http\Controllers\Admin\PageController::class, 'manage']);
    Route::post('pages/storestatic', [\App\Http\Controllers\Admin\PageController::class, 'storestatic']);
    Route::delete('pages/section/{id}', [\App\Http\Controllers\Admin\PageController::class, 'removesection']);
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class);

    Route::post('users/getuser', [\App\Http\Controllers\Admin\UserController::class, 'getuser']);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('config', \App\Http\Controllers\Admin\ConfigController::class);
    Route::post('brands/getbrand', [\App\Http\Controllers\Admin\BrandController::class, 'getbrand']);
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    Route::resource('menus', \App\Http\Controllers\Admin\MenuController::class);
  });

  Route::get('products/list-all/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'listfile']);
  Route::post('products/getproduct', [\App\Http\Controllers\Admin\ProductController::class, 'getproduct']);
  Route::post('products/postproduct', [\App\Http\Controllers\Admin\ProductController::class, 'postproduct']);
  Route::post('products/storephoto', [\App\Http\Controllers\Admin\ProductController::class, 'storephoto']);
  Route::post('products/deletephoto', [\App\Http\Controllers\Admin\ProductController::class, 'deletephoto']);
  Route::post('products/checkduplicate', [\App\Http\Controllers\Admin\ProductController::class, 'checkduplicate']);
  Route::post('products/storeduplicate', [\App\Http\Controllers\Admin\ProductController::class, 'storeduplicate']);
  Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);

  Route::post('customers/getcustomer', [\App\Http\Controllers\Admin\CustomerController::class, 'getcustomer']);
  Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);

  Route::post('orders/getorder', [\App\Http\Controllers\Admin\OrderController::class, 'getorder']);
  Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);

});

require __DIR__.'/customer.php';
require __DIR__.'/frontpage.php';