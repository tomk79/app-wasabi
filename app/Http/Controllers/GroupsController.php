<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Group;
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
		$user = Auth::user();
		$parent_group_id = $request->query('parent');
		$group = null;
		if( strlen($parent_group_id) ){
			$group = UserGroupRelation
				::where(['group_id'=>$parent_group_id, 'user_id'=>$user->id])
				->leftJoin('users', 'user_group_relations.user_id', '=', 'users.id')
				->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
				->first();
			if( !$group->count() ){
				// 条件に合うレコードが存在しない場合
				// = ログインユーザー自身が指定のグループに参加していない。
				return abort(404);
			}
		}
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
			$parent_group = null;
			if( strlen($request->parent_group_id) ){
				$parent_group = UserGroupRelation
					::where(['group_id'=>$request->parent_group_id, 'user_id'=>$user->id])
					->leftJoin('users', 'user_group_relations.user_id', '=', 'users.id')
					->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
					->first();
				if( !$parent_group->count() ){
					// 条件に合うレコードが存在しない場合
					// = ログインユーザー自身が指定のグループに参加していない。
					return abort(404);
				}

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
		$user = Auth::user();

		$group = UserGroupRelation
			::where(['group_id'=>$group_id, 'user_id'=>$user->id])
			->leftJoin('users', 'user_group_relations.user_id', '=', 'users.id')
			->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
			->first();
		if( !$group->count() ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}
		if( !$group->icon ){
			$group->icon = '/common/images/nophoto.png';
		}

		$children = Group::get_children($group_id);
		$logical_path = Group::get_logical_path($group_id);

		$root_group = null;
		if( count($logical_path) >= 2 ){
			$root_group = $logical_path[0];
		}
		$parent_group = null;
		if( count($logical_path) >= 2 ){
			$parent_group = $logical_path[count($logical_path)-1-1];
		}

		return view('groups.show', ['group'=>$group, 'root_group'=>$root_group, 'parent_group'=>$parent_group, 'logical_path' => $logical_path, 'children' => $children, 'profile' => $user]);
	}

	/**
	 * グループ編集
	 */
	public function edit($group_id)
	{
		$user = Auth::user();

		$group = UserGroupRelation
			::where(['group_id'=>$group_id, 'user_id'=>$user->id])
			->leftJoin('users', 'user_group_relations.user_id', '=', 'users.id')
			->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
			->first();
		if( !$group->count() ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}
		if( !$group->icon ){
			$group->icon = '/common/images/nophoto.png';
		}

		return view('groups.edit', ['group'=>$group, 'profile' => $user]);
	}

	/**
	 * グループ編集: 実行
	 */
	public function update($group_id, Request $request)
	{
		$user = Auth::user();

		$userGroup = UserGroupRelation
			::where(['group_id'=>$group_id, 'user_id'=>$user->id])
			->leftJoin('users', 'user_group_relations.user_id', '=', 'users.id')
			->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
			->first();
		if( !$userGroup->count() ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}
		switch( $userGroup->role ){
			case 'owner':
			case 'admin':
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
		$rules = (new StoreGroup)->rules($group_id);
		$this->validate($request, [
			'name' => $rules['name'],
			'account' => $rules['account'],
			'description' => $rules['description'],
			'icon' => $rules['icon']
		]);
		$group->name = $request->name;
		$group->account = $request->account;
		$group->description = $request->description;
		if( is_string($iconBase64) ){
			$group->icon = $iconBase64;
		}
		$group->save();

		return redirect('settings/groups/'.urlencode($group->id))->with('flash_message', 'アカウント情報を更新しました。');
	}

}
