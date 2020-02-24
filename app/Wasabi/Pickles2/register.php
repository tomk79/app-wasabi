<?php
namespace App\Wasabi\Pickles2;

class register{

	/**
	 * Wasabi App Config を生成する
	 * このメソッドは、 config/wasabi.php に登録する設定値を生成します。
	 */
	public static function config(){
		return [
			'id' => 'Pickles2',
			'name' => 'Pickles 2 Integration',
			'api' => 'App\\Wasabi\\Pickles2\\Router::api',
			'web' => 'App\\Wasabi\\Pickles2\\Router::web',
		];
	}

}
