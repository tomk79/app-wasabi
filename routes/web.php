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
		Route::post('settings/profile/set_sub_email_as_primary', 'ProfileController@set_sub_email_as_primary');
		Route::delete('settings/profile/delete_sub_email', 'ProfileController@delete_sub_email');
		Route::get('settings/withdraw', 'WithdrawController@confirm');
		Route::delete('settings/withdraw', 'WithdrawController@withdraw');
		Route::resource('settings/profile/integration/oauth_apps', 'IntegrationPassportClientController');
		Route::resource('settings/profile/apikeys', 'ProfileApiKeyController');
		Route::resource('settings/profile/foreign_accounts', 'ProfileForeignAccountController');

		// グループ一覧
		Route::get('settings/groups', 'GroupsController@index');
		Route::get('settings/groups/create', 'GroupsController@create');
		Route::post('settings/groups/create', 'GroupsController@store');
		Route::get('settings/groups/{group_id}', 'GroupsController@show');
		Route::get('settings/groups/{group_id}/edit', 'GroupsController@edit');
		Route::post('settings/groups/{group_id}/edit', 'GroupsController@update');

		// グループ: メンバー管理
		Route::resource('settings/groups/{group_id}/members', 'MembersController');

		// プロジェクト一覧
		Route::get('settings/projects', 'ProjectsController@index');
		Route::get('settings/projects/create', 'ProjectsController@create');
		Route::post('settings/projects/create', 'ProjectsController@store');
		Route::get('settings/projects/{project_id}', 'ProjectsController@show');
		Route::get('settings/projects/{project_id}/edit', 'ProjectsController@edit');
		Route::post('settings/projects/{project_id}/edit', 'ProjectsController@update');
		Route::resource('settings/projects/{project_id}/members', 'ProjectMembersController');
		Route::get('settings/projects/{project_id}/wasabiapps', 'ProjectWasabiAppsController@index');
		Route::post('settings/projects/{project_id}/wasabiapps/update', 'ProjectWasabiAppsController@update');

		// プロジェクト: ホームページ(公開プロフィール)
		Route::get('pj/{project_id}', 'HomeController@project');


		// WASABI App Integration
		Route::middleware(function($request, Closure $next){
			$parsed_ids = \App\Helpers\wasabiHelper::get_project_app_id_by_request_uri();
			$project_id = $parsed_ids['project_id'];
			$app_id = $parsed_ids['app_id'];
			if( !strlen($project_id) || !strlen($app_id) ){
				return;
			}

			$project = \App\Project::find($project_id);
			if( !$project ){
				// 条件に合うレコードが存在しない場合
				// = ログインユーザー自身が指定のプロジェクトに参加していない。
				return abort(404);
			}

			$user = \Illuminate\Support\Facades\Auth::user();
			$user_permissions = \App\Project::get_user_permissions($project_id, $user->id);
			if( $user_permissions === false ){
				// ユーザーは所属していない
				return abort(404);
			}

			$relation = \App\ProjectWasabiappRelation::where(['project_id'=>$project->id, 'wasabiapp_id' => $app_id])
				->first();
			if( !$relation ){
				// このプロジェクトには統合されていない
				return abort(404);
			}

			\App\Helpers\wasabiHelper::push_breadclumb($project->name, '/pj/'.urlencode($project->id));

			return $next($request);
		})
			->prefix('pj/{project_id}/app/{app_id}')
			->group(function () {
				\App\Helpers\wasabiHelper::route_app_integration_web();
			});

		// グループ: ホームページ(公開プロフィール)
		Route::get('g/{account}', 'HomeController@group');

		// アカウント: ホームページ(公開プロフィール)
		Route::get('{account}', 'HomeController@index');
	});

});
