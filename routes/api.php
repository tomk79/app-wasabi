<?php

use Illuminate\Http\Request;
use App\Group;
use App\Project;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:apikey,api')
		// ↑config/auth.php で guards に登録した apikey を呼び出している。
	->group(function(){

		Route::get('/user', function (Request $request) {
			return $request->user();
		});

		Route::get('/user_info', function (Request $request) {
			$rtn = array();
			$rtn['user'] = $request->user();
			$rtn['groups'] = Group::get_user_groups( $rtn['user']->id );
			$rtn['root_groups'] = Group::get_user_root_groups( $rtn['user']->id );
			$rtn['projects'] = Project::get_user_projects( $rtn['user']->id );
			return $rtn;
		});

		Route::get('/groups/{group}/permissions', function (Group $group, Request $request) {
			return Group::get_user_permissions($group->id);
		});

		Route::get('/groups/{group}/tree', function (Group $group, Request $request) {
			return Group::get_group_tree($group->id);
		});

		Route::get('/projects/{project}/permissions', function (Project $project, Request $request) {
			return Project::get_user_permissions($project->id);
		});

		// WASABI App Integration
		Route::match(
			['get', 'post'],
			'projects/{project_id}/app/{app_id}',
			'ProjectWasabiAppsController@appIntegrationApi'
		);
		Route::match(
			['get', 'post'],
			'projects/{project_id}/app/{app_id}/{params}',
			'ProjectWasabiAppsController@appIntegrationApi'
		)->where('params', '.+');

	})
;
