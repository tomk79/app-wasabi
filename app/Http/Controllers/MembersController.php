<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Group;
use App\User;
use App\UserGroupRelation;
use App\Http\Requests\StoreGroup;
use App\Rules\Role;

class MembersController extends Controller
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
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($group_id)
	{
		$user = Auth::user();

		$user_permissions = Group::get_user_permissions($group_id, $user->id);
		if( $user_permissions === false ){
			// ユーザーは所属していない
			return abort(404);
		}

		$group = Group::find($group_id);
		if( !$group ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		$members = UserGroupRelation
			::where(['group_id'=>$group->id])
			->leftJoin('groups', function ($join) {
				$join->on('groups.id', '=', 'user_group_relations.group_id')
					->whereNull('groups.deleted_at');
			})
			->leftJoin('users', function ($join) {
				$join->on('users.id', '=', 'user_group_relations.user_id')
					->whereNull('users.deleted_at');
			})
			->whereNotNull('groups.id')
			->whereNotNull('users.id')
			->orderBy('users.email')
			->paginate(100);

		// パンくず
		\helpers\wasabiHelper::push_breadclumb('グループ', '/settings/groups');
		$logical_path = Group::get_logical_path($group_id);
		foreach( $logical_path as $logical_path_group ){
			\helpers\wasabiHelper::push_breadclumb($logical_path_group->name, '/settings/groups/'.urlencode($logical_path_group->id));
		}
		\helpers\wasabiHelper::push_breadclumb('メンバー', '/settings/groups/'.urlencode($group->id).'/members');

		return view('members.index', ['group'=>$group, 'members'=>$members, 'profile' => $user]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($group_id)
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

		$group = Group::find($group_id);

		// パンくず
		\helpers\wasabiHelper::push_breadclumb('グループ', '/settings/groups');
		$logical_path = Group::get_logical_path($group_id);
		foreach( $logical_path as $logical_path_group ){
			\helpers\wasabiHelper::push_breadclumb($logical_path_group->name, '/settings/groups/'.urlencode($logical_path_group->id));
		}
		\helpers\wasabiHelper::push_breadclumb('メンバー', '/settings/groups/'.urlencode($group->id).'/members');
		\helpers\wasabiHelper::push_breadclumb('新規');

		return view('members.create', ['group_id'=>$group_id, 'profile' => $user]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store($group_id, Request $request)
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

		$group = Group::find($group_id);
		if( !$group ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}
		$invited_user = User
			::where('email', $request->email)
			->first();

		$request->validate([
			'email' => [
				'required',
				'exists:users',
				function ($attribute, $value, $fail) use ($invited_user, $user, $group){
					if( !$invited_user ){
						$fail('招待できないユーザーです。');
					}
					if( $invited_user == $user->id ){
						$fail('自分を招待することはできません。');
					}
					$records = UserGroupRelation::where(['user_id'=>$invited_user->id, 'group_id' => $group->id])->first();
					if($records){
						$fail('すでに登録されています。');
					}
				},
			],
			'role' => [
				'required',
				new Role,
			],
		]);

		$userGroupRelation = new UserGroupRelation;
		$userGroupRelation->user_id = $invited_user->id;
		$userGroupRelation->group_id = $group->id;
		$userGroupRelation->role = $request->role;
		$userGroupRelation->save();

		return redirect('settings/groups/'.urlencode($group_id).'/members')->with('flash_message', '新しいメンバー '.$request->email.' を招待しました。');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($group_id, $invited_user_id, Request $request)
	{
		$user = Auth::user();

		$user_permissions = Group::get_user_permissions($group_id, $user->id);
		if( $user_permissions === false ){
			// ユーザーは所属していない
			return abort(404);
		}

		$group = Group::find($group_id);
		if( !$group ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		$invited_user = User::find($invited_user_id);

		$relation = $userGroupRelation = UserGroupRelation
			::where('user_id', $invited_user_id)
			->where('group_id', $group->id)
			->leftJoin('groups', function ($join) {
				$join->on('groups.id', '=', 'user_group_relations.group_id')
					->whereNull('groups.deleted_at');
			})
			->leftJoin('users', function ($join) {
				$join->on('users.id', '=', 'user_group_relations.user_id')
					->whereNull('users.deleted_at');
			})
			->whereNotNull('groups.id')
			->whereNotNull('users.id')
			->first();
		if( !$relation ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}

		// パンくず
		\helpers\wasabiHelper::push_breadclumb('グループ', '/settings/groups');
		$logical_path = Group::get_logical_path($group->id);
		foreach( $logical_path as $logical_path_group ){
			\helpers\wasabiHelper::push_breadclumb($logical_path_group->name, '/settings/groups/'.urlencode($logical_path_group->id));
		}
		\helpers\wasabiHelper::push_breadclumb('メンバー', '/settings/groups/'.urlencode($group->id).'/members');
		\helpers\wasabiHelper::push_breadclumb($invited_user->name, '/settings/groups/'.urlencode($group->id).'/members/'.urlencode($invited_user->id));

		return view('members.show', ['relation'=>$relation, 'group'=>$group, 'user' => $invited_user, 'profile' => $user]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($group_id, $invited_user_id, Request $request)
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

		$group = Group::find($group_id);
		if( !$group ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		if( $invited_user_id == $user->id ){
			// 自分を編集することはできない
			return abort(403, '自分を編集することはできません。');
		}

		$invited_user = User::find($invited_user_id);

		$relation = $userGroupRelation = UserGroupRelation
			::where('user_id', $invited_user_id)
			->where('group_id', $group->id)
			->leftJoin('groups', function ($join) {
				$join->on('groups.id', '=', 'user_group_relations.group_id')
					->whereNull('groups.deleted_at');
			})
			->leftJoin('users', function ($join) {
				$join->on('users.id', '=', 'user_group_relations.user_id')
					->whereNull('users.deleted_at');
			})
			->whereNotNull('groups.id')
			->whereNotNull('users.id')
			->first();
		if( !$relation ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}

		// パンくず
		\helpers\wasabiHelper::push_breadclumb('グループ', '/settings/groups');
		$logical_path = Group::get_logical_path($group->id);
		foreach( $logical_path as $logical_path_group ){
			\helpers\wasabiHelper::push_breadclumb($logical_path_group->name, '/settings/groups/'.urlencode($logical_path_group->id));
		}
		\helpers\wasabiHelper::push_breadclumb('メンバー', '/settings/groups/'.urlencode($group->id).'/members');
		\helpers\wasabiHelper::push_breadclumb($invited_user->name, '/settings/groups/'.urlencode($group->id).'/members/'.urlencode($invited_user->id));
		\helpers\wasabiHelper::push_breadclumb('編集');

		return view('members.edit', ['relation'=>$relation, 'group'=>$group, 'user' => $invited_user, 'profile' => $user]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update($group_id, $invited_user_id, Request $request)
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

		$group = Group::find($group_id);
		if( !$group ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		if( $invited_user_id == $user->id ){
			// 自分を編集することはできない
			return abort(403, '自分を編集することはできません。');
		}

		$invited_user = User::find($invited_user_id);

		$request->validate([
			'role' => [
				'required',
				new Role,
			],
		]);

		DB::table('user_group_relations')
			->where('user_id', $invited_user_id)
			->where('group_id', $group->id)
			->leftJoin('groups', function ($join) {
				$join->on('groups.id', '=', 'user_group_relations.group_id')
					->whereNull('groups.deleted_at');
			})
			->leftJoin('users', function ($join) {
				$join->on('users.id', '=', 'user_group_relations.user_id')
					->whereNull('users.deleted_at');
			})
			->whereNotNull('groups.id')
			->whereNotNull('users.id')
			->update(['role' => $request->role]);

		return redirect('settings/groups/'.urlencode($group->id).'/members')->with('flash_message', 'メンバー '.$invited_user->email.' の情報を更新しました。');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($group_id, $invited_user_id, Request $request)
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

		$group = Group::find($group_id);
		if( !$group ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		$invited_user = User::find($invited_user_id);

		if( !$invited_user->count() ){
			// 条件に合うレコードが存在しない場合
			return abort(403);
		}

		if( $invited_user_id == $user->id ){
			// 自分を除名することはできない
			return abort(403);
		}

		$userGroupRelation = UserGroupRelation
			::where(['user_id' => $invited_user_id, 'group_id' => $group_id])
			->delete();

		return redirect('settings/groups/'.urlencode($group_id).'/members')->with('flash_message', 'メンバー '.$invited_user->email.' をメンバーから外しました。');
	}
}
