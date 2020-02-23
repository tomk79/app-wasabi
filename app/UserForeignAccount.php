<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class UserForeignAccount extends Model
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

	/**
	 * Constructor
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

		// newした時に自動的にuuidを設定する。
		$this->attributes['id'] = Uuid::uuid4()->toString();
	}

}
