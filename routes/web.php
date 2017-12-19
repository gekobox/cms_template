<?php
Route::group(['middleware' => ['web']], function () {
    Route::get("/test-payment","PaymentsController@testPayment");
    
    Auth::routes();
    Route::get('/', function () {
        return view('page');
    });
    
    Route::get('/mollie/start-recurring-payment', 'PaymentsController@startRecurringPayment');
    Route::post('/mollie/webhook', 'PaymentsController@webhook');
    Route::get('/change-language/{locale}', 'TranslationsController@changeLocale');
    Route::get('/home', 'HomeController@index')->name('home');
});