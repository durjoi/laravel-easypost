<?php

Route::group(['namespace' => 'Demo', 'prefix' => 'demo'], function () { // , 'middleware' => 'restrictIp'
    Route::get('emailtemplate', [\App\Http\Controllers\DemoController::class, 'EmailTemplateIndex']);
    Route::get('emailtemplate/create', [\App\Http\Controllers\DemoController::class, 'EmailTemplateCreate']);
    Route::get('emailtemplate/edit', [\App\Http\Controllers\DemoController::class, 'EmailTemplateEdit']);
});