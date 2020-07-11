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

//ユーザー閲覧画面
Route::get('/', 'MeetingController@reserve');
Route::get('/input/{date}', 'MeetingController@input');
Route::post('/input/check', 'MeetingController@check');
Route::post('/reserve_meeting', 'MeetingController@reserveMeeting');

//ajax
Route::get('/meeting', 'MeetingController@ajax');

//管理者画面
Route::get('/admin', 'AdminController@getIndex');
Route::get('/setEvent', 'EventController@setEvent');
Route::get('/guest', 'AdminController@guestInfo');

//zoom API
Route::get('/zoom_user', 'MeetingController@get_users');