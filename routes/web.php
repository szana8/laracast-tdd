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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('issues', 'IssuesController@index');
Route::get('issues/create', 'IssuesController@create');
Route::post('issues', 'IssuesController@store')->middleware('must-be-confirmed');
Route::get('issues/{category}/{issue}', 'IssuesController@show');
Route::delete('issues/{category}/{issue}', 'IssuesController@destroy');
Route::get('issues/{category}', 'IssuesController@index');

Route::get('issues/{category}/{issue}/replies', 'RepliesController@index');
Route::post('issues/{category}/{issue}/replies', 'RepliesController@store');
Route::patch('replies/{reply}', 'RepliesController@update');
Route::delete('replies/{reply}', 'RepliesController@destroy');
Route::post('replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('replies/{reply}/favorites', 'FavoritesController@destroy');

Route::post('issues/{category}/{issue}/subscriptions', 'IssueSubscribeController@store')->middleware('auth');
Route::delete('issues/{category}/{issue}/subscriptions', 'IssueSubscribeController@destroy')->middleware('auth');

Route::get('profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('profiles/{user}/notifications', 'UserNotificationsController@index');
Route::delete('profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');

Route::get('/register/confirm', 'Api\RegisterConfirmationController@index');

Route::get('/api/users', 'Api\UsersController@index');
Route::post('/api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth')->name('avatar');

