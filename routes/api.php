<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([ 'prefix' => 'auth' ], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('user', 'AuthController@me');
});

Route::resource('accounts','AccountController');
Route::resource('categories','CategoryController');
Route::get('getalltransactionmonth','TransactionController@getAllTransactionMonth');
Route::resource('transactions','TransactionController');
Route::get('transactions/type/{id}','TransactionController@transactionType');