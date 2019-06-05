<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\OauthClient;

class IntegrationPassportClientController extends Controller
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

		$clients = OauthClient
			::where(['user_id'=>$user->id])
			->paginate(100);

		return view('integration.passportclients.index', ['clients'=>$clients, 'profile' => $user]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$user = Auth::user();

		return view('integration.passportclients.create', ['profile' => $user]);
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

		$oauthClient = new OauthClient;
		$oauthClient->user_id = $user->id;
		$oauthClient->name = $request->name;
		$oauthClient->redirect = $request->redirect;
		$oauthClient->secret = $secret;
		$oauthClient->personal_access_client = 0;
		$oauthClient->password_client = 0;
		$oauthClient->revoked = 0;
		$oauthClient->save();

		return redirect('settings/profile/integration/oauth_apps')->with('flash_message', '新しいアプリケーション '.$request->email.' を登録しました。');
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

		$client = OauthClient::find($id);

		return view('integration.passportclients.show', ['client'=>$client, 'profile' => $user]);
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
		$client = OauthClient::find($id);
		return view('integration.passportclients.edit', ['client'=>$client, 'profile' => $user]);
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

		$client = OauthClient::find($id);
		$client->name = $request->name;
		$client->redirect = $request->redirect;
		$client->save();

		return redirect('settings/profile/integration/oauth_apps/'.urlencode($id))->with('flash_message', 'アプリケーション '.$client->name.' の情報を更新しました。');
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
		$client = OauthClient::find($id);
		$client->delete();

		return redirect('settings/profile/integration/oauth_apps')->with('flash_message', 'アプリケーション '.$client->name.' を削除しました。');
	}
}
