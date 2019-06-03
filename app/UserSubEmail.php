<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSubEmail extends Model
{

	/**
	 * Auto Increment しない
	 */
	public $incrementing = false;

	/**
	 * 主キー
	 */
	public $primaryKey = null;

	/**
	 * モデルのタイムスタンプを更新するかの指示
	 */
	public $timestamps = false;

}
