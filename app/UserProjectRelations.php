<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProjectRelations extends Model
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
