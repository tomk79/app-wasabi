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
		if( !$user->icon ){
			$user->icon = '/common/images/nophoto.png';
		}

		// アカウント
		$account = User
			::where(['account'=>$account])
			->first();
		if( !$account->count() ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}
		if( !$account->icon ){
			$account->icon = '/common/images/nophoto.png';
		}


		return view('home.index', [
			'account'=>$account,
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
		if( !$group->count() ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}
		if( !$group->icon ){
			$group->icon = '/common/images/nophoto.png';
		}

		return view('home.index', [
			'account'=>$group,
			'profile' => $user
		]);
	}

}
