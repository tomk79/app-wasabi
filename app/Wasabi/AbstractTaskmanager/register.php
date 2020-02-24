<?php
namespace App\Wasabi\AbstractTaskmanager;

class register{

	/**
	 * Wasabi App Config を生成する
	 * このメソッドは、 config/wasabi.php に登録する設定値を生成します。
	 */
	public static function config(){
		return [
			'id' => 'AbstractTaskmanager',
			'name' => 'Task Manager',
			'api' => 'App\\Wasabi\\AbstractTaskmanager\\Router::api',
			'web' => 'App\\Wasabi\\AbstractTaskmanager\\Router::web',
		];
	}

}
