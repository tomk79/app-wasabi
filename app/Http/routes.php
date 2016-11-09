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

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

// web pages
Route::get('/home', 'HomeController@index');

Route::resource('/userApiKey', 'UserApiKeyController');
Route::resource('/projectMember', 'ProjectMemberController');
Route::resource('/project', 'ProjectController');

// API
Route::get('/api/{project_id}/version', 'Api\\VersionController@index');
Route::get('/api/{project_id}/myself', 'Api\\MyselfController@index');
