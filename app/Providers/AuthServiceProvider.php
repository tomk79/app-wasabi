<?php

namespace App\Providers;

use App\User;
use App\UserApikey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [
		// 'App\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();

		// Passportフレームワーク
		Passport::routes();

		// クロージャリクエストガード
		// https://readouble.com/laravel/5.7/ja/authentication.html#adding-custom-guards
		// 
		// config/auth.php で guards に登録した apikey から呼び出されている。
		Auth::viaRequest('apikey', function($request) {

			$apikey_str = $request->input('apikey');
			$user = null;
			if( strlen($apikey_str) && preg_match('/^([a-zA-Z0-9\-]{36})\-\-(.+)$/is', $apikey_str, $matched) ){
				$apikey_id = $matched[1];
				$apikey_decrypted = $matched[2];
				$apikey = UserApikey::find($apikey_id);
				if( $apikey ){
					$decrypt = \Crypt::decryptString($apikey->apikey);
					if($decrypt == $apikey_decrypted){
						$user = User::find($apikey->user_id);
					}
				}
			}

			return $user;
		});

	}
}
