<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// 主页
Route::get('/', function () {
    return view('welcome');
});

// 登录
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// 注册
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::get('auth/register/confirm', 'Auth\ConfirmationController@index');

// 帖子
Route::get('/threads', 'ThreadController@index')->name('threads');
Route::post('/threads', 'ThreadController@store');
Route::get('/threads/create', 'ThreadController@create');
Route::get('/threads/{channel}', 'ThreadController@index');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy');
Route::post('/threads/{channel}/{thread}/lock', 'ThreadLockController@store');

// 回复
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');
Route::patch('/replies/{reply}', 'ReplyController@update');
Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('reply.destroy');
Route::post('/replies/{reply}/best', 'ReplyBestController@store')->name('reply.best');

// 订阅
Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy');

// 点赞
Route::get('/replies/{reply}/favorites', 'FavoriteController@index');
Route::post('/replies/{reply}/favorites', 'FavoriteController@store');
Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy');

// 用户主页
Route::get('/profiles/{user}', 'ProfileController@show')->name('profile');

// 用户头像
Route::post('api/users/{user}/avatar', 'AvatarController@store');

// 通知
Route::get('/profiles/{userName}/notifications', 'NotificationController@index');
Route::delete('/profiles/{userName}/notifications/{id}', 'NotificationController@destroy');

// 用户名自动完成
Route::get('api/users', 'UserController@index');
