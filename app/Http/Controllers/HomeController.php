<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\User;
use App\Group;
use App\UserGroupRelation;
use App\Http\Requests\StoreGroup;
use App\Project;
use App\UserProjectRelation;
use App\ProjectWasabiappRelation;
use Illuminate\Support\Facades\DB;



class HomeController extends Controller
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// 各アクションの前に実行させるミドルウェア
		$this->middleware('auth');
	}

	/**
	 * アカウントホーム画面
	 */
	public function index($account){
		$user = Auth::user();
		if( !$user->icon ){
			$user->icon = url('/common/images/nophoto.png');
		}

		// アカウント
		$account = User
			::where(['account'=>$account])
			->first();
		if( !$account ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}
		if( !$account->icon ){
			$account->icon = url('/common/images/nophoto.png');
		}

		// パンくず
		\App\Helpers\wasabiHelper::push_breadclumb($account->name);

		return view('home.account', [
			'account' => $account,
			'profile' => $user
		]);
	}

	/**
	 * グループホーム画面
	 */
	public function group($account){
		$user = Auth::user();

		$group = Group
			::where('account', $account)
			->first();
		if( !$group ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}
		if( !$group->icon ){
			$group->icon = url('/common/images/nophoto_group.png');
		}

		// パンくず
		\App\Helpers\wasabiHelper::push_breadclumb($group->name);

		return view('home.group', [
			'account'=>$group,
			'profile' => $user
		]);
	}

	/**
	 * プロジェクトホーム画面
	 */
	public function project($project_id)
	{
		$user = Auth::user();

		$user_permissions = Project::get_user_permissions($project_id, $user->id);
		if( $user_permissions === false ){
			// ユーザーは所属していない
			return abort(404);
		}

		$project = Project::find($project_id);
		if( !$project ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のプロジェクトに参加していない。
			return abort(404);
		}
		if( !$project->icon ){
			$project->icon = url('/common/images/nophoto_project.png');
		}

		// パンくず
		\App\Helpers\wasabiHelper::push_breadclumb($project->name);

		$group = Group::find($project->group_id);

		$relation = UserProjectRelation::where(['user_id' => $user->id, 'project_id' => $project->id])
			->leftJoin('projects', 'user_project_relations.project_id', '=', 'projects.id')
			->orderBy('projects.name')
			->first();

		$members = UserProjectRelation::where(['project_id' => $project->id])
			->leftJoin('users', function ($join) {
				$join->on('users.id', '=', 'user_project_relations.user_id')
					->whereNull('users.deleted_at');
			})
			->whereNotNull('users.email')
			->orderBy('users.email')
			->get();

		$wasabiApps = \App\Helpers\wasabiHelper::get_app_list();
		$projectWasabiAppRelations = ProjectWasabiappRelation
			::where(['project_id'=>$project->id])
			->get();
		$relations = array();
		foreach( $projectWasabiAppRelations as $projectWasabiAppRelation ){
			$relations[$projectWasabiAppRelation->wasabiapp_id] = 1;
		}
		foreach( $wasabiApps as $num => $wasabiApp ){
			$wasabiApp = (object) $wasabiApp;
			if( array_key_exists($wasabiApp->id, $relations) && $relations[$wasabiApp->id] ){
				continue;
			}
			unset($wasabiApps[$num]);
		}

		return view(
			'home.project',
			[
				'project'=>$project,
				'group'=>$group,
				'profile' => $user,
				'relation' => $relation,
				'wasabiApps' => $wasabiApps,
				'members' => $members,
			]
		);
	}


}
