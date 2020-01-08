<?php

use Illuminate\Http\Request;

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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('book', "BookController@index");
Route::get('book/{id}', "BookController@show");
Route::post('book', "BookController@store");
Route::put('book/{id}', "BookController@update");
Route::delete('book/{id}', "BookController@destroy");

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::get('logout', 'AuthController@logout');
Route::get('user', 'AuthController@getAuthUser');
Route::post('user/books/{owner_id}', 'AuthController@show_books');

Route::get('borrow', "BorrowsController@index");