<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Group;
use App\UserGroupRelation;
use App\Project;
use App\UserProjectRelation;
use App\Http\Requests\StoreProject;

class ProjectWasabiAppsController extends Controller
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// 各アクションの前に実行させるミドルウェア
		$this->middleware('auth');

		// ナビゲーション制御
		View::share('current', "projects");
	}


	/**
	 * アプリケーション統合: Web
	 */
	public function appIntegration($project_id, $app_id, $params = null, Request $request)
	{
		$project = Project::find($project_id);
		if( !$project ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のプロジェクトに参加していない。
			return abort(404);
		}

		\App\Helpers\wasabiHelper::push_breadclumb($project->name, '/pj/'.urlencode($project->id));
		\App\Helpers\wasabiHelper::push_breadclumb('アプリケーション統合');

		$wasabi_app = \App\Helpers\wasabiHelper::create_wasabi_app($app_id);
		if( !$wasabi_app->check_app_api('web') ){
			return view(
				'projects.app.error',
				[
					'app_id' => $app_id,
					'app_name' => null,
					'error_message'=>'この App は利用できません。'
				]
			);
		}

		return $wasabi_app->execute_web($request, $project_id, $params);
	}

	/**
	 * アプリケーション統合: API
	 */
	public function appIntegrationApi($project_id, $app_id, $params = null, Request $request)
	{
		$project = Project::find($project_id);
		if( !$project ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のプロジェクトに参加していない。
			return abort(404);
		}

		$wasabi_app = \App\Helpers\wasabiHelper::create_wasabi_app($app_id);
		if( !$wasabi_app->check_app_api('api') ){
			return json_encode(
				[
					'app_id' => $app_id,
					'app_name' => null,
					'error_message'=>'この App は利用できません。'
				]
			);
		}

		return $wasabi_app->execute_api($request, $project_id, $params);
	}

}
