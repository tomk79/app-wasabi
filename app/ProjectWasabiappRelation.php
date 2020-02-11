<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class ProjectWasabiappRelation extends Model
{

	// use SoftDeletes;

	/** プライマリーキーの型 */
	protected $keyType = 'string';

	/**
	 * 主キーが複合
	 */
	protected $primaryKey = array('project_id', 'wasabiapp_id');

	/**
	 * Auto Increment しない
	 */
	public $incrementing = false;

	/**
	 * 日付へキャストする属性
	 *
	 * @var array
	 */
	// protected $dates = [
	// 	'created_at',
	// 	'updated_at',
	// 	// 'deleted_at',
	// ];

	/**
	 * Constructor
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

}
