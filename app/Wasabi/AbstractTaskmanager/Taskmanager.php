<?php

namespace App\Wasabi\AbstractTaskmanager;

use Illuminate\Support\Facades\Auth;
use App\UserForeignAccount;
use App\Wasabi\AbstractTaskmanager\Models\WasabiappAbstractTaskmanagerProjectConf;

class Taskmanager
{

	private $project_id;

	public function __construct($project_id){
		$this->project_id = $project_id;
	}

	/**
	 * 接続先サービス上のユーザー情報を得る
	 */
	public function get_foreign_user_info(){
		$user = Auth::user();

		$project_conf = WasabiappAbstractTaskmanagerProjectConf::where([
			'project_id'=>$this->project_id,
		])->first();
		if( !$project_conf ){
			return false;
		}

		$account = UserForeignAccount::where([
			'user_id'=>$user->id,
			'foreign_service_id'=>$project_conf->foreign_service_id,
			'space'=>$project_conf->space,
		])->first();
		if( !$account ){
			return false;
		}
		$auth_info = json_decode($account->auth_info);

		if( $project_conf->foreign_service_id == 'backlog' ){
			$url = $project_conf->space.'/api/v2/users/myself?apiKey='.urlencode($auth_info->apikey);
		}else{
			return false;
		}

		$content = file_get_contents($url);
		$orig_foreign_user_info = json_decode($content);

		$foreign_user_info = new \stdClass();
		$foreign_user_info->name = $orig_foreign_user_info->name;
		$foreign_user_info->email = $orig_foreign_user_info->mailAddress;
		$foreign_user_info->foreign_service_id = $project_conf->foreign_service_id;
		$foreign_user_info->space = $project_conf->space;
		$foreign_user_info->orig = $orig_foreign_user_info;

		return $foreign_user_info;
	}

}
