<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroupRelation extends Model
{

	/**
	 * Auto Increment しない
	 */
	public $incrementing = false;

	/**
	 * 主キーが複合
	 */
	protected $primaryKey = array('user_id', 'group_id');

	/**
	 * モデルのタイムスタンプを更新するかの指示
	 */
	public $timestamps = false;

}
