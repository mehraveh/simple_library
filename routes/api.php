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


// AUTH ROUTES
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::get('logout', 'AuthController@logout');
Route::get('user', 'AuthController@getAuthUser');

//BOOKS RESTFUL API
Route::get('book', "BookController@index");
Route::get('book/{id}', "BookController@show");
Route::post('book', "BookController@store");
Route::put('book/{id}', "BookController@update");
Route::delete('book/{id}', "BookController@destroy");

Route::get('user/books/', 'AuthController@show_my_books');
Route::get('user/borrows/', 'AuthController@show_my_borrows');

//BORROWS RESTFUL API
Route::get('borrow', "BorrowsController@index");
Route::post('borrow/{id}', "BorrowsController@show");
Route::post('borrow', "BorrowsController@store");
Route::put('borrow/{id}', "BorrowsController@update");
Route::delete('borrow/{id}', "BorrowsController@destroy");
