<?php

namespace App\Wasabi\AbstractTaskmanager;

use Illuminate\Support\Facades\Auth;
use App\UserForeignAccount;
use App\Wasabi\AbstractTaskmanager\Models\WasabiappAbstractTaskmanagerProjectConf;

class Taskmanager
{

	private $user;
	private $project_id;
	private $projectConf;
	private $foreignAccount;

	public function __construct($project_id){
		$this->user = Auth::user();
		$this->project_id = $project_id;

		$this->projectConf = WasabiappAbstractTaskmanagerProjectConf::where([
			'project_id'=>$this->project_id,
		])->first();

		$this->foreignAccount = null;
		if($this->projectConf){
			$this->foreignAccount = UserForeignAccount::where([
				'user_id'=>$this->user->id,
				'foreign_service_id'=>$this->projectConf->foreign_service_id,
				'space'=>$this->projectConf->space,
			])->first();
		}
	}

	/**
	 * APIリクエストを送る
	 */
	private function call_foreign_api( $api_path ){
		if( !$this->projectConf ){
			return false;
		}
		if( !$this->foreignAccount ){
			return false;
		}

		$auth_info = json_decode($this->foreignAccount->auth_info);

		if( $this->projectConf->foreign_service_id == 'backlog' ){
			$url = $this->projectConf->space.$api_path;
			$url .= (!preg_match('/\?/',$url) ? '?' : '&').'apiKey='.urlencode($auth_info->apikey);
		}else{
			return false;
		}

		$content = file_get_contents(
			$url,
			false,
			stream_context_create(array(
				'http' => array(
					'method' => 'GET',
					'ignore_errors' => true,
				))
			)
		);
		$orig_json = json_decode($content);

		if( $this->projectConf->foreign_service_id == 'backlog' ){
			if( !$orig_json ){
				return false;
			}
			if( is_object($orig_json) && property_exists( $orig_json, 'errors' ) ){
				return false;
			}
		}

		return $orig_json;
	}

	/**
	 * 接続先サービス上のプロジェクト情報を得る
	 */
	public function get_foreign_space_info(){
		$foreign_space_info = new \stdClass();

		$orig_space_info = null;
		if( $this->projectConf->foreign_service_id == 'backlog' ){
			$orig_space_info = $this->call_foreign_api('/api/v2/space');
			if(!$orig_space_info){
				return false;
			}
			$foreign_space_info->id = $orig_space_info->spaceKey;
			$foreign_space_info->name = $orig_space_info->name;
			$foreign_space_info->foreign_service_id = $this->projectConf->foreign_service_id;
		}else{
			return false;
		}

		$foreign_space_info->orig = $orig_space_info;

		return $foreign_space_info;
	}

	/**
	 * 接続先サービス上のプロジェクト情報を得る
	 */
	public function get_foreign_project_info(){
		$foreign_project_info = new \stdClass();

		$orig_proj_info = null;
		if( $this->projectConf->foreign_service_id == 'backlog' ){
			$orig_proj_info = $this->call_foreign_api('/api/v2/projects/'.urlencode($this->projectConf->foreign_project_id));
			if(!$orig_proj_info){
				return false;
			}
			$foreign_project_info->id = $orig_proj_info->projectKey;
			$foreign_project_info->name = $orig_proj_info->name;
			$foreign_project_info->foreign_service_id = $this->projectConf->foreign_service_id;
			$foreign_project_info->foreign_project_id = $orig_proj_info->id;
		}else{
			return false;
		}

		$foreign_project_info->orig = $orig_proj_info;

		return $foreign_project_info;
	}

	/**
	 * 接続先サービス上のユーザー情報を得る
	 */
	public function get_foreign_user_info(){
		$foreign_user_info = new \stdClass();

		$orig_foreign_user_info = null;
		if( $this->projectConf->foreign_service_id == 'backlog' ){
			$orig_foreign_user_info = $this->call_foreign_api('/api/v2/users/myself');
			if(!$orig_foreign_user_info){
				return false;
			}
			$foreign_user_info->name = $orig_foreign_user_info->name;
			$foreign_user_info->email = $orig_foreign_user_info->mailAddress;
			$foreign_user_info->foreign_service_id = $this->projectConf->foreign_service_id;
			$foreign_user_info->space = $this->projectConf->space;
			$foreign_user_info->foreign_user_id = $orig_foreign_user_info->id;
		}else{
			return false;
		}

		$foreign_user_info->orig = $orig_foreign_user_info;

		return $foreign_user_info;
	}

	/**
	 * チケットの一覧を得る
	 */
	public function get_ticket_list(){
		$ticket_list = new \stdClass();
		$user_info = $this->get_foreign_user_info();
		$proj_info = $this->get_foreign_project_info();

		$orig_ticket_list = null;
		if( $this->projectConf->foreign_service_id == 'backlog' ){
			$orig_ticket_list = $this->call_foreign_api(
				'/api/v2/issues'
					.'?projectId[]='.urlencode($proj_info->foreign_project_id)
					.'&assigneeId[]='.urlencode($user_info->foreign_user_id)
			);
			if(!$orig_ticket_list){
				return false;
			}
			// $ticket_list->name = $orig_ticket_list->name;
			// $ticket_list->email = $orig_ticket_list->mailAddress;
			// $ticket_list->foreign_service_id = $this->projectConf->foreign_service_id;
			// $ticket_list->space = $this->projectConf->space;
		}else{
			return false;
		}

		$ticket_list->orig = $orig_ticket_list;

		return $ticket_list;
	}

}
