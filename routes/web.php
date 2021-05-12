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

use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DividendController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\GainController;
use App\Http\Controllers\Google2FA\AuthController;
use App\Http\Controllers\GraphController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\StockController;

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);

Route::prefix('google2fa')
    ->as('google2fa.')
    ->group(function () {
        Route::get('enable', [AuthController::class, 'enable'])
            ->name('enable');

        Route::post('check', [AuthController::class, 'check'])
            ->name('check');

        Route::post('authenticate', [AuthController::class, 'authenticate'])
            ->name('authenticate');

        Route::get('disable', [AuthController::class, 'disable'])
            ->name('disable');
    });

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('graph/{stock}', [GraphController::class, 'index'])
    ->name('graph');

Route::resource('stocks', StockController::class);
Route::resource('shares', ShareController::class);
Route::resource('dividends', DividendController::class);
Route::resource('gains', GainController::class)
    ->only('index');
Route::resource('exchanges', ExchangeController::class);
Route::resource('currencies', CurrencyController::class);
