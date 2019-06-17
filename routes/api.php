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

Route::middleware('auth:api')
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

		Route::get('/projects/{project}/permissions', function (Project $project, Request $request) {
			return Project::get_user_permissions($project->id);
		});

	})
;
