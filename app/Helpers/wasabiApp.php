<?php
namespace App\Helpers;

class wasabiApp{

	private $app_id;
	private $app_settings;

	/**
	 * Constructor
	 */
	public function __construct($app_id){
		$this->app_id = $app_id;
		$list = wasabiHelper::get_app_list();
		$this->app_settings = false;
		foreach( $list as $row ){
			$row = (object) $row;
			if( $row->id == $app_id ){
				$this->app_settings = $row;
				break;
			}
		}
	}

	/**
	 * AppがAPIを提供しているか調べる
	 */
	public function check_app_api($api_name){
		if( !$this->app_settings ){
			return false;
		}
		return property_exists($this->app_settings, $api_name);
	}

	/**
	 * Webページを実行する
	 */
	public function execute_web($requestm, $project_id, $params){
		$params = trim($params);
		// $params = preg_replace('/\/+/s', '/', $params);
		$params = preg_replace('/^\/+/s', '', $params);
		$params = preg_replace('/\/+$/s', '', $params);
		$params = explode('/', $params);

		$rtn = call_user_func_array( $this->app_settings->web, array($requestm, $project_id, $params) );
		if( preg_match( '/^\s*\<\!doctype/si', $rtn ) ){
			return $rtn;
		}
		return view(
			'projects.app.index',
			[
				'app_id'=>$this->app_settings->id,
				'app_name'=>$this->app_settings->name,
				'main'=>$rtn,
			]
		);
	}
}
