<?php
namespace helpers;

class wasabiHelper{

	/**
	 * 役割の一覧を取得する
	 */
	public static function getRoleList(){
		return array(
			'owner' => 'オーナー',
			'manager' => 'マネージャー',
			'member' => 'メンバー',
			'observer' => 'オブザーバー',
			'partner' => 'パートナー',
		);
	}

	/**
	 * 役割名を取得する
	 */
	public static function roleLabel($role){
		$roleList = self::getRoleList();
		if( array_key_exists( $role, $roleList ) ){
			return $roleList[$role];
		}
		return $role;
	}

}
