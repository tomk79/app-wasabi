<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\UserApikey;
use Ramsey\Uuid\Uuid;

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

		$apikeys = UserApikey
			::where(['user_id'=>$user->id])
			->paginate(100);

		// パンくず
		\helpers\wasabiHelper::push_breadclumb('プロフィール', '/settings/profile');
		\helpers\wasabiHelper::push_breadclumb('API Keys', '/settings/profile/apikeys');

		return view('profile.apikeys.index', ['apikeys'=>$apikeys, 'profile' => $user]);
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
				'string',
				'max:255',
			],
			'description' => [
				'string',
				'max:255',
			],
		]);

		$new_apikey = md5($user->id.'-'.time()).'-'.Uuid::uuid4()->toString();

		$user_apikey = new UserApikey;
		$user_apikey->user_id = $user->id;
		$user_apikey->name = $request->name;
		$user_apikey->description = $request->description;
		$user_apikey->apikey = \Crypt::encryptString($new_apikey);
		$user_apikey->save();

		return redirect('settings/profile/apikeys')->with('flash_message', '新しい API Key を登録しました。');
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

		$apikey = UserApikey::find($id);

		// パンくず
		\helpers\wasabiHelper::push_breadclumb('プロフィール', '/settings/profile');
		\helpers\wasabiHelper::push_breadclumb('API Keys', '/settings/profile/apikeys');
		\helpers\wasabiHelper::push_breadclumb($apikey->name, '/settings/profile/apikeys/'.urlencode($apikey->id));

		return view('profile.apikeys.show', ['apikey'=>$apikey, 'profile' => $user]);
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
		$apikey = UserApikey::find($id);
		// パンくず
		\helpers\wasabiHelper::push_breadclumb('プロフィール', '/settings/profile');
		\helpers\wasabiHelper::push_breadclumb('API Keys', '/settings/profile/apikeys');
		\helpers\wasabiHelper::push_breadclumb($apikey->name, '/settings/profile/apikeys/'.urlencode($apikey->id));
		\helpers\wasabiHelper::push_breadclumb('編集');
		return view('profile.apikeys.edit', ['apikey'=>$apikey, 'profile' => $user]);
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
				'string',
				'max:255',
			],
			'description' => [
				'string',
				'max:255',
			],
		]);

		$apikey = UserApikey::find($id);
		$apikey->name = $request->name;
		$apikey->description = $request->description;
		$apikey->save();

		return redirect('settings/profile/apikeys/'.urlencode($id))->with('flash_message', 'API Key '.$apikey->name.' の情報を更新しました。');
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
		$apikey = UserApikey::find($id);
		$apikey->delete();

		return redirect('settings/profile/apikeys')->with('flash_message', 'API Key '.$apikey->name.' を削除しました。');
	}
}
