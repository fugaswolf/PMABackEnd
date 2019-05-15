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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
});

Route::get('profile', 'UserController@profile');

Route::group(['middleware' => 'auth'], function() {
    Route::get('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@user');
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@user');

    Route::group(['middleware' => 'role:admin'], function() {
        // Route::get('/entries/show', 'EntryController@show');
    });

    Route::post('users/create', 'UserController@create');
    Route::resource('/users', 'UserController');
    Route::delete('/users/{user}', 'UserController@destroy');

    Route::resource('/customers', 'CustomerController');
    Route::post('customers/create', 'CustomerController@create');
    Route::delete('/customers/{customer}', 'CustomerController@destroy');

    Route::resource('/projects', 'ProjectController'); //alleen de manager kan aan deze route fso
    Route::get('/projects/{id}', 'ProjectController@show');
    Route::get('/projectsWithActivities', 'ProjectController@indexWithActivities');
    Route::post('/projects/create', 'ProjectController@create');
    Route::delete('/projects/{project}', 'ProjectController@destroy'); // zo idd

    Route::resource('/activities', 'ActivityController');
    Route::post('activities/create', 'ActivityController@create');
    Route::delete('/activities/{activity}', 'ActivityController@destroy');
    Route::post('/activities', 'ActivityController@showActivitiesByProject');
    Route::get('/activitiesByProject/{id}', 'ActivityController@activitiesByProject');

    Route::get('/entries/showAll', 'EntryController@showAll');
    Route::resource('/entries', 'EntryController');
    Route::get('/entries/show', 'EntryController@show');
    Route::get('/entries/showPG', 'EntryController@showPG');
    Route::post('/entries/syncAll', 'EntryController@syncAll');


    Route::get('/reports/totalEmployees', 'ReportsController@totalEmployees');
    Route::get('/reports/totalEntries', 'ReportsController@totalEntries');
    Route::get('/reports/totalTodaysEntries', 'ReportsController@totalTodaysEntries');
    Route::get('/reports/totalProjects', 'ReportsController@totalProjects');
    Route::get('/reports/totalProjectsSales', 'ReportsController@totalProjectsSales');
    Route::get('/reports/totalTodaysEntriesTime', 'ReportsController@totalTodaysEntriesTime');
    Route::get('/reports/totalEntriesTime', 'ReportsController@totalEntriesTime');

    /* MOBILE REPORTS*/
    Route::get('/reports/totalEntriesMobile', 'ReportsController@totalEntriesMobile');
    Route::get('/reports/totalTodaysEntriesMobile', 'ReportsController@totalTodaysEntriesMobile');
    Route::get('/reports/totalTodaysEntriesTimeMobile', 'ReportsController@totalTodaysEntriesTimeMobile');
    Route::get('/reports/totalEntriesTimeMobile', 'ReportsController@totalEntriesTimeMobile');
    
});

/* BACKUP

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

Route::resource('/projects', 'ProjectController')->middleware('auth:api'); //alleen de manager kan aan deze route fso
Route::get('/projects/{id}', 'ProjectController@show')->middleware('auth:api');
Route::get('/projectsWithActivities', 'ProjectController@indexWithActivities')->middleware('auth:api');
Route::post('/projects/create', 'ProjectController@create')->middleware('auth:api');
Route::delete('/projects/{project}', 'ProjectController@destroy')->middleware('auth:api');

Route::resource('/activities', 'ActivityController')->middleware('auth:api');
Route::post('activities/create', 'ActivityController@create')->middleware('auth:api');
Route::delete('/activities/{activity}', 'ActivityController@destroy')->middleware('auth:api');
Route::post('/activities', 'ActivityController@showActivitiesByProject')->middleware('auth:api');
Route::get('/activitiesByProject/{id}', 'ActivityController@activitiesByProject')->middleware('auth:api');

Route::resource('/entries', 'EntryController')->middleware('auth:api');
Route::get('/entries/show', 'EntryController@show')->middleware('auth:api');
Route::get('/entries/showPG', 'EntryController@showPG')->middleware('auth:api');
*/