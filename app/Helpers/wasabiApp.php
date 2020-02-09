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
		if( !array_key_exists( $app_id, $list ) ){
			return;
		}
		$this->app_settings = $list[$app_id];
	}

	/**
	 * AppがAPIを提供しているか調べる
	 */
	public function check_app_api($api_name){
		if( !$this->app_settings ){
			return false;
		}
		return array_key_exists($api_name, $this->app_settings);
	}

}
