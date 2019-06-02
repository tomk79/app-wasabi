<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use App\User;
use App\UsersEmailChange;
use App\Http\Requests\StoreUser;
use App\Mail\UsersEmailChange as UsersEmailChangeMail;

class ProfileController extends Controller
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
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$user = Auth::user();
		if( !$user->icon ){
			$user->icon = '/common/images/nophoto.png';
		}

		return view('profile.index', ['profile' => $user]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit()
	{
		$user = Auth::user();
		if( !$user->icon ){
			$user->icon = '/common/images/nophoto.png';
		}

		return view('profile.edit', ['profile' => $user]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		$user = Auth::user();
		$userStore = new StoreUser();

		$iconBase64 = null;
		if( strlen($_FILES['icon']['tmp_name']) && is_file($_FILES['icon']['tmp_name']) ){
			$iconBase64 = 'data:'.$_FILES['icon']['type'].';base64,'.base64_encode( file_get_contents($_FILES['icon']['tmp_name']) );
		}

		$request->validate([
			'name' => $userStore->rules($user->id)['name'],
			'account' => $userStore->rules($user->id)['account'],
		]);
		$user->name = $request->name;
		$user->account = $request->account;
		if( strlen($request->password) ){
			$request->validate([
				'password' => [
					function($attribute, $value, $fail) use ($request){
						if( $request->password != $request->{'password-confirm'} ){
							$fail($attribute.' is invalid.');
						}
					}
				],
			]);

			if( $request->password == $request->{'password-confirm'} ){
				$user->password = Hash::make($request->password);
			}
		}
		if( is_string($iconBase64) ){
			$user->icon = $iconBase64;
		}
		$user->save();
		return redirect('settings/profile')->with('flash_message', 'プロフィールを更新しました。');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit_email()
	{
		$user = Auth::user();
		return view('profile.edit_email', ['profile' => $user]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update_email(Request $request)
	{
		$user = Auth::user();
		$userStore = new StoreUser();
		$request->validate([
			'email' => $userStore->rules($user->id)['email']
		]);

		// ランダムなトークンを生成
		$random_token = rand(10000, 99999).'-'.rand(1000, 9999).'-'.uniqid();

		if( $request->method == '1' ){
			// 古いメールアドレスも残したまま、新しいメールアドレスをログインに使う

			// 同じユーザーのレコードがある場合を想定して、
			// 先に削除する
			$usersEmailChange = UsersEmailChange
				::where(['user_id'=>$user->id])
				->delete();

			$usersEmailChange = new UsersEmailChange();
			$usersEmailChange->user_id = $user->id;
			$usersEmailChange->email = $request->email;
			$usersEmailChange->token = $random_token;
			$usersEmailChange->created_at = date('Y-m-d H:i:s');
			$usersEmailChange->save();

			// 確認メール送信
			Mail::to($usersEmailChange->email)
				->send(new UsersEmailChangeMail($usersEmailChange));

		}elseif( $request->method == '2' ){
			// ログインに使うメールアドレスはそのままにして、新しいメールアドレスを追加する

			// 確認メール送信
			Mail::to($usersEmailChange->email)
				->send(new UsersEmailChangeMail($usersEmailChange));

		}else{
			// 古いメールアドレスを上書きし、新しいメールアドレスをログインに使う

			// 同じユーザーのレコードがある場合を想定して、
			// 先に削除する
			$usersEmailChange = UsersEmailChange
				::where(['user_id'=>$user->id])
				->delete();

			$usersEmailChange = new UsersEmailChange();
			$usersEmailChange->user_id = $user->id;
			$usersEmailChange->email = $request->email;
			$usersEmailChange->token = $random_token;
			$usersEmailChange->created_at = date('Y-m-d H:i:s');
			$usersEmailChange->save();

			// 確認メール送信
			Mail::to($usersEmailChange->email)
				->send(new UsersEmailChangeMail($usersEmailChange));

		}


		return redirect('settings/profile/edit_email_mailsent');
	}

	/**
	 * 確認メール送信後の画面
	 */
	public function update_email_mailsent(Request $request)
	{
		$user = Auth::user();
		return view('profile.edit_email_mailsent', ['profile' => $user]);
	}

	/**
	 * 確認メールに記載のリンクを受け、完了する
	 */
	public function update_email_update(Request $request)
	{
		$user = Auth::user();

		// 同じユーザーのレコードがある場合を想定して、
		// 先に削除する
		$usersEmailChange = UsersEmailChange
			::where(['user_id'=>$user->id])
			->first();
		if( !$usersEmailChange ){
			return abort(403, '仮メールアドレスが登録されていません。');
		}
		if( $usersEmailChange->token != $request->token ){
			return abort(403, 'この操作を継続する権限がないか、トークンの有効期限が切れています。');
		}
		if( strtotime($usersEmailChange->created_at) < time() - 60*60 ){
			return abort(403, 'この操作を継続する権限がないか、トークンの有効期限が切れています。');
		}

		// 成立
		$user->email = $usersEmailChange->email;
		$user->save();

		// 一時テーブルからレコードを削除する
		$usersEmailChange = UsersEmailChange
			::where(['user_id'=>$user->id])
			->delete();

		return redirect('settings/profile')->with('flash_message', 'メールアドレスを変更しました。');
	}

}
