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

Route::get('logout', 'AuthController@logout')->middleware('jwt.verify');

Route::get('user', 'AuthController@getAuthUser')->middleware('jwt.verify');

Route::post('edit', 'AuthController@edit')->middleware('jwt.verify');


//BOOKS RESTFUL API
Route::get('book', "BooksController@index")->middleware('jwt.verify');

Route::get('book/{id}', "BooksController@show")->middleware('jwt.verify');

Route::post('book', "BooksController@store")->middleware('jwt.verify');

Route::put('book/{id}', "BooksController@update")->middleware('jwt_super_user.verify');

Route::delete('book/{id}', "BooksController@destroy")->middleware('jwt_super_user.verify');

#OTHER REQUESTS
Route::get('user/books/', 'AuthController@show_my_books')->middleware('jwt.verify');

Route::get('user/borrows/', 'AuthController@show_my_borrows')->middleware('jwt.verify');

Route::get('user/users/', 'AuthController@users')->middleware('jwt_super_user.verify');

//BORROWS RESTFUL API
Route::get('borrow', "BorrowsController@index")->middleware('jwt_super_user.verify');

Route::get('borrow/{id}', "BorrowsController@show")->middleware('jwt_super_user.verify');

Route::post('borrow', "BorrowsController@store")->middleware('jwt.verify');

Route::put('borrow/{id}', "BorrowsController@update")->middleware('jwt_super_user.verify');

Route::delete('borrow/{id}', "BorrowsController@destroy")->middleware('jwt_super_user.verify');

Route::get('accept/{id}', "BorrowsController@accept")->middleware('jwt.verify');

Route::get('reject/{id}', "BorrowsController@reject")->middleware('jwt.verify');

Route::get('take_back/{id}', "BorrowsController@take_back")->middleware('jwt.verify');

#Route::get('sendmail','MailController@send_email');