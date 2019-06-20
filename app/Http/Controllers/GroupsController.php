<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Group;
use App\Project;
use App\UserGroupRelation;
use App\Http\Requests\StoreGroup;

class GroupsController extends Controller
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// 各アクションの前に実行させるミドルウェア
		$this->middleware('auth');

		// ナビゲーション制御
		View::share('current', "groups");
	}

	/**
	 * グループ一覧の表示
	 */
	public function index()
	{
		// パンくず
		\helpers\wasabiHelper::push_breadclumb('グループ', '/settings/groups');

		$user = Auth::user();
		$groups = UserGroupRelation::where('user_id', $user->id)
			->leftJoin('users', 'user_group_relations.user_id', '=', 'users.id')
			->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
			->orderBy('groups.account')
			->paginate(5);

		return view('groups.index', ['profile' => $user, 'groups' => $groups]);
	}

	/**
	 * 新規グループ作成
	 */
	public function create(Request $request)
	{
		// パンくず
		\helpers\wasabiHelper::push_breadclumb('グループ', '/settings/groups');

		$user = Auth::user();

		$parent_group_id = $request->query('parent');
		$group = null;
		if( strlen($parent_group_id) ){

			$user_permissions = Group::get_user_permissions($parent_group_id, $user->id);
			if( $user_permissions === false ){
				// ユーザーは所属していない
				return abort(404);
			}
			if( !$user_permissions['editable'] ){
				// 権限がありません
				return abort(403, 'このグループを編集する権限がありません。');
			}

			$group = Group::find($parent_group_id);
			if( !$group->count() ){
				// 条件に合うレコードが存在しない場合
				// = ログインユーザー自身が指定のグループに参加していない。
				return abort(404);
			}

			$logical_path = Group::get_logical_path($group->id);
			foreach( $logical_path as $logical_path_group ){
				\helpers\wasabiHelper::push_breadclumb($logical_path_group->name, '/settings/groups/'.urlencode($logical_path_group->id));
			}
		}

		\helpers\wasabiHelper::push_breadclumb('新規グループ');
		return view('groups.create', ['profile' => $user, 'parent' => $group]);
	}

	/**
	 * 新規グループ作成: 実行
	 */
	public function store(StoreGroup $request)
	{
		$user = Auth::user();

		$group = new Group;
		$rules = $request->rules();
		$request->validate([
			'name' => $rules['name'],
			'account' => $rules['account'],
			'description' => $rules['description']
		]);
		$group->name = $request->name;
		$group->account = $request->account;
		$group->description = $request->description;
		$group->creator_user_id = $user->id;

		if( strlen($request->parent_group_id) ){
			$user_permissions = Group::get_user_permissions($request->parent_group_id, $user->id);
			if( $user_permissions === false ){
				// ユーザーは所属していない
				return abort(404);
			}
			if( !$user_permissions['editable'] ){
				// 権限がありません
				return abort(403, 'このグループを編集する権限がありません。');
			}

			$parent_group = Group
				::where(['id'=>$request->parent_group_id])
				->first();
			if( !$parent_group ){
				// 条件に合うレコードが存在しない場合
				// = ログインユーザー自身が指定のグループに参加していない。
				return abort(404);
			}

			$group->parent_group_id = $parent_group->id;
			$group->root_group_id = $parent_group->id;
		}

		$group->save();

		$userGroupRelation = new UserGroupRelation;
		$userGroupRelation->user_id = $user->id;
		$userGroupRelation->group_id = $group->id;
		$userGroupRelation->role = 'owner';
		$userGroupRelation->save();

		return redirect('settings/groups')->with('flash_message', __('Created new group.'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($group_id, Request $request)
	{
		// パンくず
		\helpers\wasabiHelper::push_breadclumb('グループ', '/settings/groups');

		$user = Auth::user();

		$user_permissions = Group::get_user_permissions($group_id, $user->id);
		if( $user_permissions === false ){
			// ユーザーは所属していない
			return abort(404);
		}

		$group = Group::find($group_id);
		if( !$group->count() ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}
		if( !$group->icon ){
			$group->icon = url('/common/images/nophoto_group.png');
		}

		$children = Group::get_children($group_id);
		$logical_path = Group::get_logical_path($group_id);
		foreach( $logical_path as $logical_path_group ){
			\helpers\wasabiHelper::push_breadclumb($logical_path_group->name, '/settings/groups/'.urlencode($logical_path_group->id));
		}

		$root_group = null;
		if( count($logical_path) >= 2 ){
			$root_group = $logical_path[0];
		}
		$parent_group = null;
		if( count($logical_path) >= 2 ){
			$parent_group = $logical_path[count($logical_path)-1-1];
		}

		$relation = UserGroupRelation::where(['user_id' => $user->id, 'group_id' => $group->id])
			->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
			->orderBy('groups.name')
			->first();

		$members = UserGroupRelation::where(['group_id' => $group->id])
			->leftJoin('users', 'user_group_relations.user_id', '=', 'users.id')
			->orderBy('users.name')
			->get();

		$group_tree = Group::get_group_tree($group->id);

		$projects = Project::get_group_projects($group->id);

		return view(
			'groups.show',
			[
				'group'=>$group,
				'root_group'=>$root_group,
				'parent_group'=>$parent_group,
				'logical_path' => $logical_path,
				'children' => $children,
				'profile' => $user,
				'relation' => $relation,
				'members' => $members,
				'group_tree' => $group_tree,
				'projects' => $projects,
			]
		);
	}

	/**
	 * グループ編集
	 */
	public function edit($group_id)
	{
		// パンくず
		\helpers\wasabiHelper::push_breadclumb('グループ', '/settings/groups');

		$user = Auth::user();

		$user_permissions = Group::get_user_permissions($group_id, $user->id);
		if( $user_permissions === false ){
			// ユーザーは所属していない
			return abort(404);
		}
		if( !$user_permissions['editable'] ){
			// 権限がありません
			return abort(403, 'このグループを編集する権限がありません。');
		}

		$group = Group::find($group_id);
		if( !$group->count() ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}
		if( !$group->icon ){
			$group->icon = url('/common/images/nophoto_group.png');
		}

		$root_group = Group::find($group->root_group_id);
		$parent_group = Group::find($group->parent_group_id);
		$sub_groups = Group::get_sub_groups($group->root_group_id);

		$logical_path = Group::get_logical_path($group_id);
		foreach( $logical_path as $logical_path_group ){
			\helpers\wasabiHelper::push_breadclumb($logical_path_group->name, '/settings/groups/'.urlencode($logical_path_group->id));
		}
		\helpers\wasabiHelper::push_breadclumb('編集');

		return view('groups.edit', ['group'=>$group, 'root_group'=>$root_group, 'parent'=>$parent_group, 'sub_groups'=>$sub_groups, 'profile' => $user]);
	}

	/**
	 * グループ編集: 実行
	 */
	public function update($group_id, Request $request)
	{
		$user = Auth::user();

		$user_permissions = Group::get_user_permissions($group_id, $user->id);
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

		$iconBase64 = null;
		if( strlen($_FILES['icon']['tmp_name']) && is_file($_FILES['icon']['tmp_name']) ){
			$iconBase64 = 'data:'.$_FILES['icon']['type'].';base64,'.base64_encode( file_get_contents($_FILES['icon']['tmp_name']) );
		}

		$group = Group::find($group_id);
		if( !$group->count() ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}
		$rules = (new StoreGroup)->rules($group_id);
		$this->validate($request, [
			'name' => $rules['name'],
			'account' => $rules['account'],
			'description' => $rules['description'],
			'parent_group_id' => $rules['parent_group_id'],
			'icon' => $rules['icon']
		]);
		$group->name = $request->name;
		$group->account = $request->account;
		$group->description = $request->description;
		if( $group->root_group_id ){
			$group->parent_group_id = $request->parent_group_id;
		}
		if( is_string($iconBase64) ){
			$group->icon = $iconBase64;
		}
		$group->save();

		return redirect('settings/groups/'.urlencode($group->id))->with('flash_message', 'アカウント情報を更新しました。');
	}

}
