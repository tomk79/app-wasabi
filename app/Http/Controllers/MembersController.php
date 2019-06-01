<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Group;
use App\User;
use App\UserGroupRelation;
use App\Http\Requests\StoreGroup;
use App\Rules\Role;

class MembersController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($account)
	{
		$user = Auth::user();

		$group = Group
			::where('account', $account)
			->first();
		if( !$group ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		$group = UserGroupRelation
			::where(['group_id'=>$group->id, 'user_id'=>$user->id])
			->leftJoin('users', 'user_group_relations.user_id', '=', 'users.id')
			->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
			->first();
		if( !$group->count() ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}

		$members = UserGroupRelation
			::where(['group_id'=>$group->id])
			->leftJoin('users', 'user_group_relations.user_id', '=', 'users.id')
			->orderBy('email')
			->paginate(100);
		if( !$members->count() ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}

		return view('members.index', ['group'=>$group, 'members'=>$members, 'profile' => $user]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($account)
	{
		$user = Auth::user();
		return view('members.create', ['account'=>$account, 'profile' => $user]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store($account, Request $request)
	{
		$user = Auth::user();

		$group = Group
			::where('account', $account)
			->first();
		if( !$group ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}
		$invited_user = User
			::where('email', $request->email)
			->first();
		$invited_user_id = null;
		if( $invited_user ){
			$invited_user_id = $invited_user->id;
		}

		$request->validate([
			'email' => [
				'required',
				'exists:users',
				function ($attribute, $value, $fail) use ($invited_user_id, $user){
					if( $invited_user_id == $user->id ){
						$fail('自分を招待することはできません。');
					}
				},
			],
			'role' => [
				'required',
				new Role,
			],
		]);

		$userGroupRelation = new UserGroupRelation;
		$userGroupRelation->user_id = $invited_user_id;
		$userGroupRelation->group_id = $group->id;
		$userGroupRelation->role = $request->role;
		$userGroupRelation->save();

		return redirect(urlencode($account).'/members')->with('flash_message', '新しいメンバー '.$request->email.' を招待しました。');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($account, $email, Request $request)
	{
		$user = Auth::user();
		$group = Group
			::where('account', $account)
			->first();
		if( !$group ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		$invited_user = User
			::where('email', $email)
			->first();
		if( !$invited_user->count() ){
			// 条件に合うレコードが存在しない場合
			return abort(403);
		}
		$invited_user_id = $invited_user->id;

		$relation = $userGroupRelation = UserGroupRelation
			::where('user_id', $invited_user_id)
			->where('group_id', $group->id)
			->first();
		if( !$relation->count() ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}
		return view('members.show', ['relation'=>$relation, 'group'=>$group, 'user' => $invited_user, 'profile' => $user]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($account, $email, Request $request)
	{
		$user = Auth::user();

		$group = Group
			::where('account', $account)
			->first();
		if( !$group ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		$invited_user = User
			::where('email', $email)
			->first();
		if( !$invited_user->count() ){
			// 条件に合うレコードが存在しない場合
			return abort(403);
		}
		$invited_user_id = $invited_user->id;
		if( $invited_user_id == $user->id ){
			// 自分を編集することはできない
			return abort(403);
		}

		$relation = $userGroupRelation = UserGroupRelation
			::where('user_id', $invited_user_id)
			->where('group_id', $group->id)
			->first();
		if( !$relation->count() ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}
		return view('members.edit', ['relation'=>$relation, 'group'=>$group, 'user' => $invited_user, 'profile' => $user]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update($account, $email, Request $request)
	{
		$user = Auth::user();

		$group = Group
			::where('account', $account)
			->first();
		if( !$group ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		$invited_user = User
			::where('email', $email)
			->first();
		if( !$invited_user->count() ){
			// 条件に合うレコードが存在しない場合
			return abort(403);
		}
		$invited_user_id = $invited_user->id;
		if( $invited_user_id == $user->id ){
			// 自分を編集することはできない
			return abort(403);
		}

		$userGroup = UserGroupRelation
			::where(['group_id'=>$group->id, 'user_id'=>$user->id])
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

		$relation = $userGroupRelation = UserGroupRelation
			::where('user_id', $invited_user_id)
			->where('group_id', $group->id)
			->first();
		if( !$relation->count() ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}

		$request->validate([
			'role' => [
				'required',
				new Role,
			],
		]);

		DB::table('user_group_relations')
			->where('user_id', $invited_user_id)
			->where('group_id', $group->id)
			->update(['role' => $request->role]);

		return redirect(urlencode($group->account).'/members')->with('flash_message', 'メンバー '.$invited_user->email.' の情報を更新しました。');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($account, $email, Request $request)
	{
		$user = Auth::user();

		$group_id = Group
			::where('account', $account)
			->first()->id;
		if( !$group_id ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		$invited_user = User
			::where('email', $email)
			->first();
		if( !$invited_user->count() ){
			// 条件に合うレコードが存在しない場合
			return abort(403);
		}
		$user_id = $invited_user->id;
		if( $user_id == $user->id ){
			// 自分を除名することはできない
			return abort(403);
		}

		$userGroupRelation = UserGroupRelation
			::where('user_id', $user_id)
			->where('group_id', $group_id)
			->delete();

		return redirect(urlencode($account).'/members')->with('flash_message', 'メンバー '.$email.' をメンバーから外しました。');
	}
}
