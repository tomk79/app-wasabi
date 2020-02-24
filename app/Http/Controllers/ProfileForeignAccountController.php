<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\UserForeignAccount;
use Ramsey\Uuid\Uuid;

class ProfileForeignAccountController extends Controller
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// 各アクションの前に実行させるミドルウェア
		$this->middleware('auth');

		// ナビゲーション制御
		View::share('current', "profile");
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$user = Auth::user();

		$foreign_accounts = UserForeignAccount
			::where(['user_id'=>$user->id])
			->paginate(10);

		// パンくず
		\App\Helpers\wasabiHelper::push_breadclumb('プロフィール', '/settings/profile');
		\App\Helpers\wasabiHelper::push_breadclumb('外部アカウント連携', '/settings/profile/foreign_accounts');

		return view('profile.foreign_accounts.index', ['foreign_accounts'=>$foreign_accounts, 'profile' => $user]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$user = Auth::user();

		// パンくず
		\App\Helpers\wasabiHelper::push_breadclumb('プロフィール', '/settings/profile');
		\App\Helpers\wasabiHelper::push_breadclumb('外部アカウント連携', '/settings/profile/foreign_accounts');
		\App\Helpers\wasabiHelper::push_breadclumb('新規');

		return view('profile.foreign_accounts.create', ['profile' => $user]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$user = Auth::user();

		$secret = Str::random(40);

		$request->validate([
			'foreign_service_id' => [
				'required',
				'string',
				'max:255',
			],
			'space' => [
				'string',
				'max:255',
			],
			'backlog_apikey' => [
				'string',
				'max:255',
			],
		]);

		$user_foreign_accounts = new UserForeignAccount;
		$user_foreign_accounts->user_id = $user->id;
		$user_foreign_accounts->foreign_service_id = $request->foreign_service_id;
		$user_foreign_accounts->space = $request->space;
		$user_foreign_accounts->auth_info = json_encode(array(
			'type' => 'apikey',
			'apikey' => $request->{'backlog-apikey'},
		));
		$user_foreign_accounts->save();

		return redirect('settings/profile/foreign_accounts')->with('flash_message', '新しい アカウント を登録しました。');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$user = Auth::user();

		$foreign_account = UserForeignAccount::find($id);

		// パンくず
		\App\Helpers\wasabiHelper::push_breadclumb('プロフィール', '/settings/profile');
		\App\Helpers\wasabiHelper::push_breadclumb('外部アカウント連携', '/settings/profile/foreign_accounts');
		\App\Helpers\wasabiHelper::push_breadclumb($foreign_account->foreign_service_id, '/settings/profile/foreign_accounts/'.urlencode($foreign_account->id));

		return view('profile.foreign_accounts.show', ['foreign_account'=>$foreign_account, 'profile' => $user]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$user = Auth::user();
		$foreign_account = UserForeignAccount::find($id);
		// パンくず
		\App\Helpers\wasabiHelper::push_breadclumb('プロフィール', '/settings/profile');
		\App\Helpers\wasabiHelper::push_breadclumb('外部アカウント連携', '/settings/profile/foreign_accounts');
		\App\Helpers\wasabiHelper::push_breadclumb($foreign_account->foreign_service_id, '/settings/profile/foreign_accounts/'.urlencode($foreign_account->id));
		\App\Helpers\wasabiHelper::push_breadclumb('編集');

		$json = json_decode($foreign_account->auth_info, true);
		$foreign_account->{'backlog-apikey'} = $json['apikey'];

		return view('profile.foreign_accounts.edit', ['foreign_account'=>$foreign_account, 'profile' => $user]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$user = Auth::user();

		$request->validate([
			'foreign_service_id' => [
				'required',
				'string',
				'max:255',
			],
			'space' => [
				'string',
				'max:255',
			],
			'backlog_apikey' => [
				'string',
				'max:255',
			],
		]);

		$foreign_account = UserForeignAccount::find($id);
		$foreign_account->foreign_service_id = $request->foreign_service_id;
		$foreign_account->space = $request->space;
		$foreign_account->auth_info = json_encode(array(
			'type' => 'apikey',
			'apikey' => $request->{'backlog-apikey'},
		));
		$foreign_account->save();

		return redirect('settings/profile/foreign_accounts/'.urlencode($id))->with('flash_message', 'アカウント '.$foreign_account->foreign_service_id.' の情報を更新しました。');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$user = Auth::user();
		$foreign_accounts = UserForeignAccount::find($id);
		$foreign_accounts->delete();

		return redirect('settings/profile/foreign_accounts')->with('flash_message', 'アカウント '.$foreign_accounts->foreign_service_id.' を削除しました。');
	}
}
