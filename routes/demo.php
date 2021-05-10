<?php

Route::group(['namespace' => 'Demo', 'prefix' => 'demo'], function () { // , 'middleware' => 'restrictIp'
    Route::get('emailtemplate', [\App\Http\Controllers\DemoController::class, 'EmailTemplateIndex']);
    Route::get('emailtemplate/create', [\App\Http\Controllers\DemoController::class, 'EmailTemplateCreate']);
    Route::get('emailtemplate/edit', [\App\Http\Controllers\DemoController::class, 'EmailTemplateEdit']);
    
    Route::get('sms', [App\Http\Controllers\FrontPageController::class, 'test']);

    Route::get('verification', [App\Http\Controllers\DemoController::class, 'VerificationPage']);
    Route::get('emailsending', [App\Http\Controllers\DemoController::class, 'EmailTester']);
});