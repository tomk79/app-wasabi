<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\User;
use App\Group;
use App\UserGroupRelation;
use App\Http\Requests\StoreGroup;
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

		$group_id = Group
			::where('account', $account)
			->first()->id;
		if( !$group_id ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		// グループ
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


		return view('home.index', [
			'group'=>$group,
			'profile' => $user
		]);
	}


	/**
	 * グループ編集
	 */
	public function edit($account)
	{
		$user = Auth::user();

		$group_id = Group
			::where('account', $account)
			->first()->id;
		if( !$group_id ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

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
	public function update($account, Request $request)
	{
		$user = Auth::user();

		$group_id = Group
			::where('account', $account)
			->first()->id;
		if( !$group_id ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

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

		return redirect(urlencode($group->account))->with('flash_message', 'アカウント情報を更新しました。');
	}

}
