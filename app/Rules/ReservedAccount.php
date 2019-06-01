<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ReservedAccount implements Rule
{
	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	}

	/**
	 * Account名の予約語をバリデート
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		switch( $value ){
			// ユーザー登録、ログイン関連
			case 'register':
			case 'login':
			case 'logout':
			case 'password':

			// 設定画面など
			case 'setting':
			case 'settings':
			case 'config':
			case 'configs':

			// ヘルプページ、ドキュメント
			case 'help':
			case 'docs':
			case 'documents':
			case 'manual':

			// その他のシステムページ
			case 'sys':
			case 'system':
			case 'api':
			case 'apis':
			case 'error':
			case 'errors':
			case 'css':
			case 'js':
			case 'svg':
			case 'common':
			case 'resources':
			case 'group':
			case 'groups':
			case 'g':

			// サービスアカウント
			case 'admin':
			case 'administrator':
			case 'support':

			// その他、ユーザーの混乱を避けるために予約するものなど
			case 'notfound':
			case 'unauthorized':
			case 'undefined':
			case 'null':
				return false;
		}
		return true;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return 'This name is reserved.';
	}
}
