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

Route::get('/', 'HomeController@index');
Route::get('/withdraw/completed', function(){
    return view('withdraw/completed');
});

Route::auth();

// web pages
Route::get('/userApiKey', 'UserApiKeyController@index');
Route::get('/userApiKey/create', 'UserApiKeyController@create');
Route::post('/userApiKey/create', 'UserApiKeyController@store');
Route::get('/userApiKey/result', 'UserApiKeyController@result');
Route::delete('/userApiKey/{hash}', 'UserApiKeyController@destroy');

Route::resource('/projectMember', 'ProjectMemberController');

Route::resource('/project', 'ProjectController');

Route::get('/profile', 'ProfileController@index');
Route::get('/profile/edit', 'ProfileController@edit');
Route::post('/profile/update', 'ProfileController@update');

Route::get('/withdraw', 'WithdrawController@confirm');
Route::delete('/withdraw', 'WithdrawController@withdraw');

// API
Route::get('/api/{project_account}', 'Api\\ProjectInfoController@index');
Route::get('/api/{project_account}/version', 'Api\\VersionController@index');
Route::get('/api/{project_account}/myself', 'Api\\MyselfController@index');
