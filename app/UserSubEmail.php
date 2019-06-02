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
	 * モデルのタイムスタンプを更新するかの指示
	 */
	public $timestamps = false;

}
