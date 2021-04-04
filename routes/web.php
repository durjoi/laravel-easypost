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

        Route::group(['prefix' => 'users'], function () {
           Route::get('/', [\App\Http\Controllers\Admin\SettingsController::class, 'Users']);
           Route::get('/create', [\App\Http\Controllers\Admin\SettingsController::class, 'CreateUser']);
           Route::post('/store', [\App\Http\Controllers\Api\ApiController::class, 'StoreUser']);
           Route::get('/{hashedid}/edit', [\App\Http\Controllers\Admin\SettingsController::class, 'EditUser']);
           Route::put('/{hashedid}', [\App\Http\Controllers\Api\ApiController::class, 'UpdateUser']);
           Route::delete('/{hashedid}', [\App\Http\Controllers\Api\ApiController::class, 'DestroyUser']);
        });
        Route::resource('config', \App\Http\Controllers\Admin\ConfigController::class);
        Route::post('brands/getbrand', [\App\Http\Controllers\Api\DatatableController::class, 'GetBrands']);
        Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
        Route::get('menus', [\App\Http\Controllers\Admin\SettingsController::class, 'Menus']);
    });

    Route::get('products/list-all/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'listfile']);
    Route::post('products/getproduct', [\App\Http\Controllers\Api\DatatableController::class, 'GetProduct']);
    Route::post('products/postproduct', [\App\Http\Controllers\Admin\ProductController::class, 'postproduct']);
    Route::post('products/storephoto', [\App\Http\Controllers\Admin\ProductController::class, 'storephoto']);
    Route::post('products/deletephoto', [\App\Http\Controllers\Admin\ProductController::class, 'deletephoto']);
    Route::post('products/checkduplicate', [\App\Http\Controllers\Admin\ProductController::class, 'checkduplicate']);
    Route::post('products/checkduplicatedevice', [\App\Http\Controllers\Admin\ProductController::class, 'checkduplicatedevice']);
    Route::post('products/storeduplicate', [\App\Http\Controllers\Admin\ProductController::class, 'storeduplicate']);
    Route::put('products/{hashedId}', [\App\Http\Controllers\Admin\ProductController::class, 'update']);
    Route::get('products/storage/{hashedId}/find', [\App\Http\Controllers\Admin\ProductController::class, 'findProductStorage']);
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);

    // Route::post('customers/getcustomer', [\App\Http\Controllers\Admin\CustomerController::class, 'getcustomer']);
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);

    
    Route::get('orders/{hashedId}/generatePDF', [\App\Http\Controllers\Admin\OrderController::class, 'generatePDF']);
    Route::get('orders/{hashedId}/orderItem', [\App\Http\Controllers\Admin\OrderController::class, 'getOrderItem']);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);

    Route::get('pagebuilder', ['as' => 'admin.pagebuilder.index', 'uses' => 'App\Http\Controllers\Admin\PageBuilderController@index']);
    Route::post('pagebuilder', ['as' => 'admin.pagebuilder.post', 'uses' => 'App\Http\Controllers\Admin\PageBuilderController@store']);
    Route::get('pagebuilder/{hashedId}', ['as' => 'admin.pagebuilder.edit', 'uses' => 'App\Http\Controllers\Admin\PageBuilderController@edit']);
    Route::put('pagebuilder/{hashedId}', ['as' => 'admin.pagebuilder', 'uses' => 'App\Http\Controllers\Admin\PageBuilderController@update']);
    Route::get('pagebuilder/{hashedid}/build', ['as' => 'admin.pagebuilder.build', 'uses' => 'App\Http\Controllers\Admin\PageBuilderController@build']);
    Route::put('pagebuilder/{id}/build', ['as' => 'admin.pagebuilder.build', 'uses' => 'App\Http\Controllers\Admin\PageBuilderController@buildPage']);
    Route::get('pagebuilder/{hashedid}/build/getcontent', ['as' => 'admin.pagebuilder.build.getcontent', 'uses' => 'App\Http\Controllers\Admin\PageBuilderController@getContent']);


    Route::get('settings/status', ['as' => 'admin.settings.status', 'uses' => 'App\Http\Controllers\Admin\SettingsController@status']);
    Route::get('settings/categories', ['as' => 'admin.settings.categories', 'uses' => 'App\Http\Controllers\Admin\SettingsController@categories']);
});



Route::group(['prefix' => 'api'], function() {
    Route::group(['middleware' => ['auth:web']], function() {
        Route::get('settings/status/{hashedId}', ['as' => 'api.settings.status', 'uses' => 'App\Http\Controllers\Api\ApiController@GetStatusDetails']);
        Route::post('settings/statuses', [App\Http\Controllers\Api\DatatableController::class, 'GetStatuses']);
        Route::patch('settings/status', ['as' => 'api.settings.status', 'uses' => 'App\Http\Controllers\Api\ApiController@PatchStatus']);
        Route::delete('settings/status/{hashedId}', ['as' => 'api.settings.status', 'uses' => 'App\Http\Controllers\Api\ApiController@DeleteStatus']);

        Route::get('orders/{hashedId}', [\App\Http\Controllers\Api\ApiController::class, 'GetOrder']);
        Route::post('orders/getorders', [\App\Http\Controllers\Api\DatatableController::class, 'getOrders']);
        Route::get('orders/{hashedId}/paymentsuccess', [\App\Http\Controllers\Api\ApiController::class, 'OrderPaymentSuccess']);
        Route::delete('order/{hashedId}/orderitem', ['as' => 'api.order.orderitem', 'uses' => 'App\Http\Controllers\Api\ApiController@DeleteOrderItem']);

        Route::get('products/{id}', ['as' => 'api.products', 'uses' => 'App\Http\Controllers\Api\ApiController@GetProduct']);
        Route::patch('products/{hashedid}', ['as' => 'api.products', 'uses' => 'App\Http\Controllers\Api\ApiController@PatchProduct']);
        
        Route::get('modules', ['as' => 'api.modules', 'uses' => 'App\Http\Controllers\Api\ApiController@GetModules']);
        Route::get('enableoptions', ['as' => 'api.enableoptions', 'uses' => 'App\Http\Controllers\Api\ApiController@GetEnableOptions']);



        Route::post('settings/users', [\App\Http\Controllers\Api\DatatableController::class, 'GetUsers']);


        Route::post('settings/categories', [App\Http\Controllers\Api\DatatableController::class, 'GetCategories']);
        Route::patch('settings/categories', ['as' => 'api.settings.categories', 'uses' => 'App\Http\Controllers\Api\ApiController@PatchCategories']);
        Route::get('settings/categories/{hashedId}', ['as' => 'api.settings.categories', 'uses' => 'App\Http\Controllers\Api\ApiController@GetCategoryDetails']);
        Route::delete('settings/categories/{hashedId}', ['as' => 'api.settings.categories', 'uses' => 'App\Http\Controllers\Api\ApiController@DeleteCategory']);

        

        Route::post('admin/customers', [App\Http\Controllers\Api\DatatableController::class, 'GetCustomers']);
        Route::patch('admin/customers/changepassword', [\App\Http\Controllers\Customer\ApiController::class, 'ChangePassword']);

        Route::get('admin/payment', [App\Http\Controllers\Admin\PaymentPaypalController::class, 'Payment']);
        Route::get('admin/payment/status', [App\Http\Controllers\Admin\PaymentPaypalController::class, 'GetPaymentStatus']);
        // Route::get('payment', 'PayPalController@payment')->name('payment');
        Route::get('admin/payment/cancel', 'PayPalController@cancel')->name('api.admin.payment.cancel');
        Route::get('admin/payment/success', 'PayPalController@success')->name('api.admin.payment.success');
    });
    Route::group(['prefix' => 'cron'], function () {
        Route::get('notifyday7', ['as' => 'api.cron.notifyday7', 'uses' => 'App\Http\Controllers\Api\ApiController@NotifyDay7']);
        Route::get('notifyday29', ['as' => 'api.cron.notifyday29', 'uses' => 'App\Http\Controllers\Api\ApiController@NotifyDay29']);
        Route::get('notifycustomerorder', ['as' => 'api.cron.notifycustomerorder', 'uses' => 'App\Http\Controllers\Api\ApiController@NotifyCustomerOrder']);
    });
});
require __DIR__.'/customer.php';
require __DIR__.'/frontpage.php';