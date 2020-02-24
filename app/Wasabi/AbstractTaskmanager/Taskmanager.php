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

		$url = 'https://pickles2.backlog.com/api/v2/users/myself?apiKey='.urlencode($auth_info->apikey);

		$content = file_get_contents($url);
		return $content;
	}

}
