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
	public function execute_web($project_id, $app_id){
		call_user_func_array( $this->app_settings->web, array($project_id, $app_id) );
	}

	/**
	 * APIを実行する
	 */
	public function execute_api($project_id, $app_id){
		$rtn = call_user_func_array( $this->app_settings->api, array($project_id, $app_id) );
		return json_encode(
			$rtn
		);
	}
}
