<?php
namespace App\Wasabi\AbstractTaskmanager;

use Route;

class Router{

	/**
	 * Wasabi App: ウェブページを実行する
	 */
	public static function web($project_id, $app_id){

		Route::get('/', '\App\Wasabi\AbstractTaskmanager\Controllers\HomeController@index');
		Route::get('/project_conf/edit', '\App\Wasabi\AbstractTaskmanager\Controllers\ProjectConfController@edit');
		Route::post('/project_conf/edit', '\App\Wasabi\AbstractTaskmanager\Controllers\ProjectConfController@save');

	}

	/**
	 * Wasabi App: APIを実行する
	 */
	public static function api($project_id, $app_id){
		Route::match(
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
