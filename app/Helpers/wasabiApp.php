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
		if( !array_key_exists( $app_id, $list ) ){
			return;
		}
		$this->app_settings = $list[$app_id];
	}

}
