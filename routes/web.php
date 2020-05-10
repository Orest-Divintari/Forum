<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes(['verify' => true]);
Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy')->name('delete_thread');
    Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');
    Route::get('/threads/create', 'ThreadController@create');
    Route::post('/threads', 'ThreadController@store')->middleware('verified');
    Route::delete('/replies/{reply}', 'ReplyController@destroy');
    Route::put('/replies/{reply}', 'ReplyController@update');

    //subscriptions
    Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store');
    Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy');

    //Favorites
    Route::post('/replies/{reply}/favorites', 'FavoriteController@store');
    Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy');

    // notifications
    Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');
    Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');

    //avatars
    Route::post('/api/users/{user}/avatar', 'UserAvatarController@store');

});

// threads

Route::get('/threads', 'ThreadController@index');
Route::get('/threads/{channel}', 'ThreadController@index');

Route::get('/threads/{channel}/{thread}', 'ThreadController@show')->name('thread_show');

//replies
Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');

//profiles
Route::get('/profiles/{user}', 'ProfileController@show')->name('profile');