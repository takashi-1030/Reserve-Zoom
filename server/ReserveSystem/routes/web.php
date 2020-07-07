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
Route::post('/', 'MeetingController@check');
Route::post('/reserve_meeting', 'MeetingController@reserveMeeting');

//zoom API
Route::get('/zoom_user', 'MeetingController@get_users');