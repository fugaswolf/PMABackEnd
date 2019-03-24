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

Route::middleware('auth:api')->get('/users', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        
    });
});

Route::post('users/create', 'UserController@create')->middleware('auth:api');
Route::resource('/users', 'UserController')->middleware('auth:api');
Route::delete('/users/{user}', 'UserController@destroy')->middleware('auth:api');
Route::get('profile', 'UserController@profile');

Route::resource('/customers', 'CustomerController')->middleware('auth:api');
Route::post('customers/create', 'CustomerController@create')->middleware('auth:api');
Route::delete('/customers/{customer}', 'CustomerController@destroy')->middleware('auth:api');

Route::resource('/projects', 'ProjectController')->middleware('auth:api');
Route::post('projects/create', 'ProjectController@create')->middleware('auth:api');
Route::delete('/projects/{project}', 'ProjectController@destroy')->middleware('auth:api');

Route::resource('/activities', 'ActivityController')->middleware('auth:api');
Route::post('activities/create', 'ActivityController@create')->middleware('auth:api');
Route::delete('/activities/{activity}', 'ActivityController@destroy')->middleware('auth:api');
