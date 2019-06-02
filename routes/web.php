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

Route::middleware(['boot'])
->group(function () {
	Route::get('/', 'StartpageController@startpage');
	Route::get('/settings/withdraw/completed', function(){
		return view('withdraw/completed');
	});

	Auth::routes(['verify' => true]);
	Route::middleware('verified')->group(function(){

		// ユーザーのプロフィール
		Route::get('settings/profile', 'ProfileController@index');
		Route::get('settings/profile/edit', 'ProfileController@edit');
		Route::post('settings/profile/edit', 'ProfileController@update');
		Route::get('settings/profile/edit_email', 'ProfileController@edit_email');
		Route::post('settings/profile/edit_email', 'ProfileController@update_email');
		Route::get('settings/profile/edit_email_mailsent', 'ProfileController@update_email_mailsent');
		Route::get('settings/profile/edit_email_update', 'ProfileController@update_email_update');
		Route::get('settings/withdraw', 'WithdrawController@confirm');
		Route::delete('settings/withdraw', 'WithdrawController@withdraw');

		// グループ一覧
		Route::get('settings/groups', 'GroupsController@index');
		Route::get('settings/groups/create', 'GroupsController@create');
		Route::post('settings/groups/create', 'GroupsController@store');
		Route::get('settings/groups/{group_id}', 'GroupsController@show');
		Route::get('settings/groups/{group_id}/edit', 'GroupsController@edit');
		Route::post('settings/groups/{group_id}/edit', 'GroupsController@update');

		// グループ: メンバー管理
		Route::resource('settings/groups/{group_id}/members', 'MembersController');


		// グループ: ホームページ
		Route::get('g/{account}', 'HomeController@group');

		// アカウント: ホームページ
		Route::get('{account}', 'HomeController@index');

	});

});