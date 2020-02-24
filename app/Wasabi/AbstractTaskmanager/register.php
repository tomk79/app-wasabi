<?php
namespace App\Wasabi\AbstractTaskmanager;

use App\User;
use App\Project;
use App\Wasabi\AbstractTaskmanager\Models\WasabiappAbstractTaskmanagerProjectConf;
use App\Wasabi\AbstractTaskmanager\Taskmanager;

class register{

	/**
	 * Wasabi App Config を生成する
	 * このメソッドは、 config/wasabi.php に登録する設定値を生成します。
	 */
	public static function config(){
		return [
			'id' => 'AbstractTaskmanager',
			'name' => 'Task Manager',
			'api' => 'App\\Wasabi\\AbstractTaskmanager\\register::apiroute',
			'web' => 'App\\Wasabi\\AbstractTaskmanager\\register::webroute',
		];
	}

	/**
	 * Wasabi App: ウェブページを実行する
	 */
	public static function webroute($project_id, $app_id){

		\Route::match(
			['get', 'post'],
			'/{params?}',
			'\App\Wasabi\AbstractTaskmanager\Controllers\HomeController@index'
		)->where('params', '.+');

	}

	/**
	 * Wasabi App: APIを実行する
	 */
	public static function apiroute($project_id, $app_id){
		\Route::match(
			['get', 'post'],
			'/{params?}',
			function(Request $request, $project_id, $app_id, $params = null){
				$params = trim($params);
				// $params = preg_replace('/\/+/s', '/', $params);
				$params = preg_replace('/^\/+/s', '', $params);
				$params = preg_replace('/\/+$/s', '', $params);
				$params = trim($params);
				if( strlen($params) ){
					$params = explode('/', $params);
				}else{
					$params = array();
				}


				return [
					'result' => false,
					'error_message' => 'API not found.',
				];

			}
		)->where('params', '.+');

	}

}
