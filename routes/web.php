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
//search
Route::get('/threads/search', 'SearchController@show')->name('threads.search');
Route::view('scan', 'scan');

Route::group(['middleware' => 'auth'], function () {
    Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy')->name('threads.delete');
    Route::patch('/threads/{channel}/{thread}', 'ThreadController@update')->name('threads.update');
    Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');
    Route::get('/threads/create', 'ThreadController@create');
    Route::post('/threads', 'ThreadController@store')->middleware('verified')->name('threads.store');
    Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('replies.delete');
    Route::put('/replies/{reply}', 'ReplyController@update');

    //best reply
    Route::post('/replies/{reply}/best_reply', 'BestReplyController@store')->name('best-replies.store');
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

    //lock threads
    Route::post('locked-threads/{thread}', 'LockedThreadController@store')->name('locked-threads.store')->middleware('admin');
    Route::delete('locked-threads/{thread}', 'LockedThreadController@destroy')->name('locked-threads.destroy')->middleware('admin');

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
