<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\UserApikey;

class ProfileApiKeyController extends Controller
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

		$clients = UserApikey
			::where(['user_id'=>$user->id])
			->paginate(100);

		// パンくず
		\helpers\wasabiHelper::push_breadclumb('プロフィール', '/settings/profile');
		\helpers\wasabiHelper::push_breadclumb('API Keys', '/settings/profile/apikeys');

		return view('profile.apikeys.index', ['clients'=>$clients, 'profile' => $user]);
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
		\helpers\wasabiHelper::push_breadclumb('プロフィール', '/settings/profile');
		\helpers\wasabiHelper::push_breadclumb('API Keys', '/settings/profile/apikeys');
		\helpers\wasabiHelper::push_breadclumb('新規');

		return view('profile.apikeys.create', ['profile' => $user]);
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
			'name' => [
				'required',
				'max:255',
			],
			'redirect' => [
				'required',
				'url',
			],
		]);

		$user_apikey = new UserApikey;
		$user_apikey->user_id = $user->id;
		$user_apikey->name = $request->name;
		$user_apikey->redirect = $request->redirect;
		$user_apikey->secret = $secret;
		$user_apikey->personal_access_client = 0;
		$user_apikey->password_client = 0;
		$user_apikey->revoked = 0;
		$user_apikey->save();

		return redirect('settings/profile/apikeys')->with('flash_message', '新しいアプリケーション '.$request->email.' を登録しました。');
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

		$client = UserApikey::find($id);

		// パンくず
		\helpers\wasabiHelper::push_breadclumb('プロフィール', '/settings/profile');
		\helpers\wasabiHelper::push_breadclumb('API Keys', '/settings/profile/apikeys');
		\helpers\wasabiHelper::push_breadclumb($client->name, '/settings/profile/apikeys/'.urlencode($client->id));

		return view('profile.apikeys.show', ['client'=>$client, 'profile' => $user]);
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
		$client = UserApikey::find($id);
		// パンくず
		\helpers\wasabiHelper::push_breadclumb('プロフィール', '/settings/profile');
		\helpers\wasabiHelper::push_breadclumb('API Keys', '/settings/profile/apikeys');
		\helpers\wasabiHelper::push_breadclumb($client->name, '/settings/profile/apikeys/'.urlencode($client->id));
		\helpers\wasabiHelper::push_breadclumb('編集');
		return view('profile.apikeys.edit', ['client'=>$client, 'profile' => $user]);
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
			'name' => [
				'required',
			],
			'redirect' => [
				'required',
			],
		]);

		$client = UserApikey::find($id);
		$client->name = $request->name;
		$client->redirect = $request->redirect;
		$client->save();

		return redirect('settings/profile/apikeys/'.urlencode($id))->with('flash_message', 'アプリケーション '.$client->name.' の情報を更新しました。');
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
		$client = UserApikey::find($id);
		$client->delete();

		return redirect('settings/profile/apikeys')->with('flash_message', 'アプリケーション '.$client->name.' を削除しました。');
	}
}
