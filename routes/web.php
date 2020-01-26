<?php

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

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes...
Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);

Route::group(['prefix' => 'google2fa', 'as' => 'google2fa.', 'namespace' => 'Google2FA'], function () {
    Route::get('/enable', 'AuthController@enable')->name('enable');
    Route::post('/check', 'AuthController@check')->name('check');
    Route::post('/authenticate', 'AuthController@authenticate')->name('authenticate');
    Route::get('/disable', 'AuthController@disable')->name('disable');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('graph/{stock}', 'GraphController@index')->name('graph');

Route::resource('stocks', 'StockController');
Route::resource('shares', 'ShareController');
Route::resource('dividends', 'DividendController');
Route::get('gains', 'GainController@index')->name('gains');
Route::resource('exchanges', 'ExchangeController');
Route::resource('currencies', 'CurrencyController');
