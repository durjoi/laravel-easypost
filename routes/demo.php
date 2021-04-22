<?php

Route::group(['namespace' => 'Demo', 'prefix' => 'demo'], function () { // , 'middleware' => 'restrictIp'
    Route::get('emailtemplate', [\App\Http\Controllers\DemoController::class, 'EmailTemplateIndex']);
    Route::get('emailtemplate/create', [\App\Http\Controllers\DemoController::class, 'EmailTemplateCreate']);
    Route::get('emailtemplate/edit', [\App\Http\Controllers\DemoController::class, 'EmailTemplateEdit']);
	// Route::get('reverb/cronOrders', 'ReverbController@cronOrders')->name('reverb.cronOrders');
	// Route::get('reverb/updateReverbInventoryDaily', 'ReverbController@updateReverbInventoryDaily')->name('reverb.updateReverbInventoryDaily');
	// Route::get('reverb/getListings', 'ReverbController@getListings')->name('reverb.getListings');
	// Route::post('reverb/post-order', 'ReverbController@postOrder');
	// Route::resource('reverb', 'ReverbController');
});