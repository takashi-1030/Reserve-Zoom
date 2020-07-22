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

//管理者画面
Route::get('/admin', 'AdminController@getIndex');
Route::get('/admin/edit/{id}', 'AdminController@edit');
Route::post('/admin/edit/{id}', 'AdminController@editCheck');
Route::post('/admin/edit/done/{id}', 'AdminController@editDone');
Route::get('/admin/delete/{id}', 'AdminController@delete');
Route::get('/admin/delete/done/{id}', 'AdminController@deleteDone');
Route::get('/admin/guest', 'AdminController@guestInfo');
Route::get('/admin/search', 'AdminController@search');
Route::post('/admin/search/done', 'AdminController@searchDone');
Route::get('/setEvent', 'EventController@setEvent');

//ajax
Route::get('/meeting', 'MeetingController@ajax');
Route::get('/meeting/edit', 'AdminController@ajax');

//zoom API
Route::get('/zoom_user', 'MeetingController@get_users');