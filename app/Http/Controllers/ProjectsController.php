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

class ProjectsController extends Controller
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
	 * プロジェクト一覧の表示
	 */
	public function index()
	{
		// パンくず
		\App\Helpers\wasabiHelper::push_breadclumb('プロジェクト', '/settings/projects');

		$user = Auth::user();
		$projects = UserProjectRelation::where('user_id', $user->id)
			->leftJoin('users', 'user_project_relations.user_id', '=', 'users.id')
			->leftJoin('projects', 'user_project_relations.project_id', '=', 'projects.id')
			->orderBy('projects.name')
			->paginate(5);

		return view('projects.index', ['profile' => $user, 'projects' => $projects]);
	}

	/**
	 * 新規プロジェクト作成
	 */
	public function create(Request $request)
	{
		// パンくず
		\App\Helpers\wasabiHelper::push_breadclumb('プロジェクト', '/settings/projects');
		\App\Helpers\wasabiHelper::push_breadclumb('新規プロジェクト');

		$user = Auth::user();
		$groups = UserGroupRelation::where('user_id', $user->id)
			->leftJoin('users', 'user_group_relations.user_id', '=', 'users.id')
			->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
			->orderBy('groups.account')
			->get();

		return view('projects.create', ['profile' => $user, 'groups' => $groups]);
	}

	/**
	 * 新規プロジェクト作成: 実行
	 */
	public function store(StoreProject $request)
	{
		$user = Auth::user();

		$project = new Project;
		$rules = $request->rules();
		$request->validate([
			'name' => $rules['name'],
			'description' => $rules['description']
		]);
		$project->name = $request->name;
		$project->description = $request->description;
		$project->group_id = $request->group_id;
		$project->creator_user_id = $user->id;
		$project->save();

		$userProjectRelation = new UserProjectRelation;
		$userProjectRelation->user_id = $user->id;
		$userProjectRelation->project_id = $project->id;
		$userProjectRelation->role = 'owner';
		$userProjectRelation->save();

		return redirect('settings/projects')->with('flash_message', __('Created new project.'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($project_id, Request $request)
	{
		// パンくず
		\App\Helpers\wasabiHelper::push_breadclumb('プロジェクト', '/settings/projects');

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

		return view(
			'projects.show',
			[
				'project'=>$project,
				'group'=>$group,
				'profile' => $user,
				'relation' => $relation,
				'members' => $members,
			]
		);
	}

	/**
	 * プロジェクト編集
	 */
	public function edit($project_id)
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
			return abort(403, 'このプロジェクトを編集する権限がありません。');
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

		\App\Helpers\wasabiHelper::push_breadclumb($project->name, '/pj/'.urlencode($project->id));
		\App\Helpers\wasabiHelper::push_breadclumb('編集');

		$group = Group::find($project->group_id);
		$groups = UserGroupRelation::where('user_id', $user->id)
			->leftJoin('users', 'user_group_relations.user_id', '=', 'users.id')
			->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
			->orderBy('groups.account')
			->get();

		return view('projects.edit', ['project'=>$project, 'group' => $group, 'groups' => $groups, 'profile' => $user]);
	}

	/**
	 * プロジェクト編集: 実行
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
			return abort(403, 'このプロジェクトを編集する権限がありません。');
		}

		$iconBase64 = null;
		if( strlen($_FILES['icon']['tmp_name']) && is_file($_FILES['icon']['tmp_name']) ){
			$iconBase64 = 'data:'.$_FILES['icon']['type'].';base64,'.base64_encode( file_get_contents($_FILES['icon']['tmp_name']) );
		}

		$project = Project::find($project_id);
		if( !$project ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のプロジェクトに参加していない。
			return abort(404);
		}
		$rules = (new StoreProject)->rules($project_id);
		$this->validate($request, [
			'name' => $rules['name'],
			'description' => $rules['description'],
			'icon' => $rules['icon']
		]);
		$project->name = $request->name;
		$project->group_id = $request->group_id;
		$project->description = $request->description;
		if( is_string($iconBase64) ){
			$project->icon = $iconBase64;
		}
		$project->save();

		return redirect('pj/'.urlencode($project->id))->with('flash_message', 'アカウント情報を更新しました。');
	}

	/**
	 * アプリケーション統合
	 */
	public function appIntegration($project_id, $app_id, $params = null, Request $request)
	{
		$project = Project::find($project_id);
		if( !$project ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のプロジェクトに参加していない。
			return abort(404);
		}

		\App\Helpers\wasabiHelper::push_breadclumb('プロジェクト', '/settings/projects');
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
	 * アプリケーション統合
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
