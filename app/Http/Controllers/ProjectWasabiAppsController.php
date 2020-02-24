<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Group;
use App\UserGroupRelation;
use App\Project;
use App\UserProjectRelation;
use App\ProjectWasabiappRelation;
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
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($project_id)
	{
		// パンくず
		\App\Helpers\wasabiHelper::push_breadclumb('プロジェクト', '/settings/projects');

		$user = Auth::user();

		$user_permissions = Project::get_user_permissions($project_id, $user->id);
		if( $user_permissions === false ){
			// ユーザーは所属していない
			return abort(404);
		}
		if( !$user_permissions['editable'] ){
			// 権限がありません
			return abort(403, 'このグループを編集する権限がありません。');
		}

		$project = Project::find($project_id);
		if( !$project ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		\App\Helpers\wasabiHelper::push_breadclumb($project->name, '/settings/projects/'.urlencode($project->id));
		\App\Helpers\wasabiHelper::push_breadclumb('アプリケーション統合');

		$relations = array();
		$projectWasabiAppRelations = ProjectWasabiappRelation
			::where(['project_id'=>$project->id])->get();
		foreach( $projectWasabiAppRelations as $projectWasabiAppRelation ){
			$relations[$projectWasabiAppRelation->wasabiapp_id] = 1;
		}

		$wasabiApps = \App\Helpers\wasabiHelper::get_app_list();

		return view(
			'projectwasabiapps.index',
			[
				'project'=>$project,
				'relations'=>$relations,
				'apps'=>$wasabiApps,
				'profile' => $user,
			]
		);
	}

	/**
	 * グループ編集: 実行
	 */
	public function update($project_id, Request $request)
	{
		$user = Auth::user();

		$user_permissions = Project::get_user_permissions($project_id, $user->id);
		if( $user_permissions === false ){
			// ユーザーは所属していない
			return abort(404);
		}
		if( !$user_permissions['editable'] ){
			// 権限がありません
			return abort(403, 'このグループを編集する権限がありません。');
		}

		switch( $user_permissions['role'] ){
			case 'owner':
			case 'manager':
				break;
			default:
				// このグループを編集する権限がありません。
				return abort(403, 'このグループを編集する権限がありません。');
		}

		$project = Project::find($project_id);
		if( !$project ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		$wasabiApps = \App\Helpers\wasabiHelper::get_app_list();
		foreach( $wasabiApps as $wasabiApp ){
			$wasabiApp = (object) $wasabiApp;
			$relation = ProjectWasabiappRelation::where(['project_id'=>$project->id, 'wasabiapp_id' => $wasabiApp->id])
				->first();
			if( $request->get($wasabiApp->id) ){
				if( !$relation ){
					$relation = new ProjectWasabiappRelation();
					$relation->project_id = $project->id;
					$relation->wasabiapp_id = $wasabiApp->id;
				}
				$relation->save();
			}else{
				if( $relation ){
					// $relation->delete();
					ProjectWasabiappRelation::where(['project_id'=>$project->id, 'wasabiapp_id' => $wasabiApp->id])->delete();
				}
			}
		}

		return redirect('settings/projects/'.urlencode($project->id).'/wasabiapps')
			->with('flash_message', 'アプリケーション統合を更新しました。');
	}

}
