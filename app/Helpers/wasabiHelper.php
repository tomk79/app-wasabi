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

	/**
	 * アイコンのURLを整形
	 */
	public static function icon($url_icon, $type = null){
		if( strlen($url_icon) ){
			return $url_icon;
		}
		if( $type == 'group' ){
			return url('/common/images/nophoto_group.png');
		}elseif( $type == 'project' ){
			return url('/common/images/nophoto_project.png');
		}
		return url('/common/images/nophoto.png');
	}

	/**
	 * アイコンのimg要素を整形
	 */
	public static function icon_img($url_icon, $type = null, $width = null){
		$url = self::icon($url_icon, $type);
		$class = 'account';

		if( $type == 'group' ){
			$class = 'group';
		}elseif( $type == 'project' ){
			$class = 'project';
		}

		$style = '';
		if( strlen($width) ){
			$style .= ' style="';
			$style .= 'width: '.htmlspecialchars( $width ).';';
			$style .= '"';
		}

		return '<img src="'.htmlspecialchars($url).'" alt="" class="'.htmlspecialchars($class).'-icon"'.$style.' />';
	}
}
