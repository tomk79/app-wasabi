<?php

use Illuminate\Http\Request;
use App\Group;
use App\Project;
use App\UserProjectRelation;

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

Route::middleware('auth:apikey,api', 'throttle:60,1')
		// ↑config/auth.php で guards に登録した apikey を呼び出している。
	->group(function(){

		// User
		Route::get('/user', function (Request $request) {
			$rtn = (array) $request->user();
			$rtn['result'] = true;
			$rtn['error_message'] = null;
			return $rtn;
		});

		Route::get('/user_info', function (Request $request) {
			$rtn = array();
			$rtn['result'] = true;
			$rtn['error_message'] = null;
			$rtn['user'] = $request->user();
			$rtn['groups'] = Group::get_user_groups( $rtn['user']->id );
			$rtn['root_groups'] = Group::get_user_root_groups( $rtn['user']->id );
			$rtn['projects'] = Project::get_user_projects( $rtn['user']->id );
			return $rtn;
		});

		// Group
		Route::get('/groups/{group}/permissions', function (Group $group, Request $request) {
			$rtn = (array) Group::get_user_permissions($group->id);
			$rtn['result'] = true;
			$rtn['error_message'] = null;
			return $rtn;
		});

		Route::get('/groups/{group}/tree', function (Group $group, Request $request) {
			$rtn = (array) Group::get_group_tree($group->id);
			$rtn['result'] = true;
			$rtn['error_message'] = null;
			return $rtn;
		});

		// Project
		Route::get('/projects/{project}/permissions', function (Project $project, Request $request) {
			$rtn = (array) Project::get_user_permissions($project->id);
			$rtn['result'] = true;
			$rtn['error_message'] = null;
			return $rtn;
		});

		Route::get('/projects/{project}/members', function (Project $project, Request $request) {
			$members = UserProjectRelation::where(['project_id' => $project->id])
				->leftJoin('users', 'users.id', '=', 'user_project_relations.user_id')
				->get();
			$rtn = array();
			$rtn['result'] = true;
			$rtn['error_message'] = null;
			$rtn['members'] = array();
			foreach($members as $member){
				$row = array();
				$row['name'] = $member['name'];
				$row['user_id'] = $member['id'];
				$row['role'] = $member['role'];
				$row['icon'] = $member['icon'];
				$row['email'] = $member['email'];
				$row['lang'] = $member['lang'];
				array_push($rtn['members'], $row);

			}
			return $rtn;
		});

		// WASABI App Integration
		Route::prefix('projects/{project_id}/app/{app_id}')
			->middleware(function($request, Closure $next){
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
					return;
				}

				$user = \Illuminate\Support\Facades\Auth::user();
				$user_permissions = \App\Project::get_user_permissions($project_id, $user->id);
				if( $user_permissions === false ){
					// ユーザーは所属していない
					return;
				}

				$relation = \App\ProjectWasabiappRelation::where(['project_id'=>$project->id, 'wasabiapp_id' => $app_id])
					->first();
				if( !$relation ){
					// このプロジェクトには統合されていない
					return;
				}
				return $next($request);
			})
			->group(function () {
				\App\Helpers\wasabiHelper::route_app_integration_api();
			});

	})
;

Route::fallback(
	function(Request $request){
		http_response_code(404);
		$rtn = array();
		$rtn['result'] = false;
		$rtn['error_message'] = 'API not found.';
		return $rtn;
	}
);
