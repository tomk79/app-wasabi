<?php
namespace App\Wasabi\Pickles2;

class register{

	/**
	 * Wasabi App Config を生成する
	 * このメソッドは、 config/wasabi.php に登録する設定値を生成します。
	 */
	public static function config(){
		return [
			'id' => 'pickles2',
			'name' => 'Pickles 2 Integration',
			'api' => 'App\\Wasabi\\Pickles2\\register::api',
			'web' => 'App\\Wasabi\\Pickles2\\register::web',
		];
	}

	/**
	 * Wasabi App: ウェブページを実行する
	 */
	public static function web($request, $project_id, $params){
		ob_start();
		var_dump($project_id, $params);
		$fin = ob_get_clean();

		return view(
			'App\Wasabi\Pickles2::index',
			['main'=>$fin]
		);
	}

	/**
	 * Wasabi App: APIを実行する
	 */
	public static function api($request, $project_id, $params){
		ob_start();
		var_dump($project_id, $params);
		$fin = ob_get_clean();

		return [
			'result' => true,
			'error_message' => null,
			'fin' => $fin,
		];
	}

}
