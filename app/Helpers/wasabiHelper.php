<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Group;
use App\UserGroupRelation;
use App\Project;
use App\UserProjectRelation;
use App\ProjectWasabiappRelation;
use App\Http\Requests\StoreProject;

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

	/**
	 * パンくずに情報を追加する
	 */
	public static function push_breadclumb( $label, $href = null ){
		$global = View::shared('global');
		if( !is_object($global) ){
			$global = json_decode('{}');
			View::share('global', $global);
		}
		if( !property_exists($global, 'breadcrumb') || !is_array( $global->breadcrumb ) ){
			$global->breadcrumb = array();
			self::push_breadclumb( (Auth::user() ? 'Dashboard' : 'Home'), '/' );
		}
		array_push($global->breadcrumb, json_decode(json_encode(array(
			'href' => $href,
			'label' => $label,
		))));
		return true;
	}

	/**
	 * Appのリストを取得する
	 */
	public static function get_app_list(){
		$list = (array) config('wasabi.app');
		return $list;
	}

	/**
	 * WASABI App のルーティング: web
	 */
	public static function route_app_integration_web(){
		$request_path = $_SERVER['REQUEST_URI'];
		$project_id = null;
		$app_id = null;
		if(preg_match('/^.*?\/pj\/([a-zA-Z0-9\-\_]+)\/app\/([a-zA-Z0-9\-\_]+)/s', $request_path, $matched)){
			$project_id = $matched[1];
			$app_id = $matched[2];
		}
		if( !strlen($project_id) || !strlen($app_id) ){
			return;
		}

		$wasabi_app = \App\Helpers\wasabiHelper::create_wasabi_app($app_id);
		if( !$wasabi_app->check_app_api('web') ){
			return view(
				'projects.app.error',
				[
					'app_id' => $app_id,
					'app_name' => null,
					'error_message'=>'この App は利用できません。'
				]
			);
		}

		return $wasabi_app->execute_web($project_id, $app_id);
	}

	/**
	 * WASABI App のルーティング: api
	 */
	public static function route_app_integration_api(){
		$request_path = $_SERVER['REQUEST_URI'];
		$project_id = null;
		$app_id = null;
		if(preg_match('/^.*?\/projects\/([a-zA-Z0-9\-\_]+)\/app\/([a-zA-Z0-9\-\_]+)/s', $request_path, $matched)){
			$project_id = $matched[1];
			$app_id = $matched[2];
		}
		if( !strlen($project_id) || !strlen($app_id) ){
			return;
		}

		$wasabi_app = \App\Helpers\wasabiHelper::create_wasabi_app($app_id);
		if( !$wasabi_app->check_app_api('api') ){
			return json_encode(
				[
					'app_id' => $app_id,
					'app_name' => null,
					'error_message'=>'この App は利用できません。'
				]
			);
		}

		return $wasabi_app->execute_api($project_id, $app_id);
	}

	/**
	 * WASABI App オブジェクトを生成する
	 */
	public static function create_wasabi_app($app_id){
		$list = self::get_app_list();
		// if( !array_key_exists($app_id, $list) ){
		// 	return false;
		// }
		$wasabi_app = new wasabiApp($app_id);
		return $wasabi_app;
	}

}
