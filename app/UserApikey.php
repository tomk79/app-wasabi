<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class UserApikey extends Model
{

	use SoftDeletes;

	/** プライマリーキーの型 */
	protected $keyType = 'string';

	/**
	 * Auto Increment しない
	 */
	public $incrementing = false;

	/**
	 * 日付へキャストする属性
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];

}
