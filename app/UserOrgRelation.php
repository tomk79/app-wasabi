<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserOrgRelation extends Model
{

	/**
	 * Auto Increment しない
	 */
	public $incrementing = false;

	/**
	 * 主キーが複合
	 */
	protected $primaryKey = array('user_id', 'org_id');

	/**
	 * モデルのタイムスタンプを更新するかの指示
	 */
	public $timestamps = false;

}
