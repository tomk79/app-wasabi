<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Group extends Model
{
	/** プライマリーキーの型 */
	protected $keyType = 'string';

	/** プライマリーキーは自動連番か？ */
	public $incrementing = false;

	/**
	 * 日付へキャストする属性
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	/**
	 * 属性に対するモデルのデフォルト値
	 *
	 * @var array
	 */
	protected $attributes = [
		'private_flg' => 0,
	];

	/**
	 * Constructor
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

		// newした時に自動的にuuidを設定する。
		$this->attributes['id'] = Uuid::uuid4()->toString();
	}

	/**
	 * 組織の、ルートからの階層を取得する
	 */
	static public function get_logical_path( $group_id ){

		$rtn = array();
		$current_group_id = $group_id;
		while( 1 ){
			if( count($rtn) >= 20 ){
				break;
			}
			$current_group = Group::where(['id'=>$current_group_id])->first();
			array_push($rtn, $current_group);
			if( $current_group->parent_group_id && $current_group->root_group_id ){
				$current_group_id = $current_group->parent_group_id;
				continue;
			}
			break;
		}

		$rtn = array_reverse($rtn);

		return $rtn;
	}

	/**
	 * 子組織の一覧を取得する
	 */
	static public function get_children( $group_id ){
		$rtn = Group::where(['parent_group_id'=>$group_id])->get();
		return $rtn;
	}

	/**
	 * 兄弟組織の一覧を取得する
	 */
	static public function get_bros( $group_id ){
		$group = Group::where(['id'=>$group_id])->first();
		if( !strlen($group->parent_group_id) || !strlen($group->root_group_id) ){
			return array();
		}
		$rtn = Group::get_children( $group->id );
		return $rtn;
	}
}
