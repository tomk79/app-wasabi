<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserProjectRelation;
use Ramsey\Uuid\Uuid;

class Project extends Model
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
	 * プロジェクトに対するユーザーの権限を取得する
	 * 
	 * ユーザーの role が発見できない場合(=プロジェクトに参加していない場合)、
	 * false を返します。
	 */
	static public function get_user_permissions( $project_id, $user_id = null ){
		$rtn = array(
			'role' => false,
			'has_membership' => false,
			'has_partnership' => false,
			'has_observership' => false,
			'editable' => false,
			'visitable' => false,
			'findable' => false,
		);
		if( !strlen($user_id) ){
			$user = Auth::user();
			$user_id = $user->id;
		}

		$relation = UserProjectRelation
			::where(['project_id'=>$project_id, 'user_id'=>$user_id])
			->leftJoin('users', 'user_project_relations.user_id', '=', 'users.id')
			->leftJoin('projects', 'user_project_relations.project_id', '=', 'projects.id')
			->first();
		if(!$relation){
			return false;
		}
		$rtn['role'] = $relation->role;
		switch($rtn['role']){
			case 'owner':
			case 'manager':
				$rtn['editable'] = true;
				$rtn['visitable'] = true;
				$rtn['findable'] = true;
				$rtn['has_membership'] = true;
				break;
			case 'member':
				$rtn['visitable'] = true;
				$rtn['findable'] = true;
				$rtn['has_membership'] = true;
				break;
			case 'observer':
				$rtn['visitable'] = true;
				$rtn['findable'] = true;
				$rtn['has_observership'] = true;
				break;
			case 'partner':
				$rtn['findable'] = true;
				$rtn['has_partnership'] = true;
				break;
			default:
				break;
		}

		if( !$rtn['role'] && !$rtn['has_membership'] && !$rtn['has_partnership'] ){
			$rtn = false;
		}
		return $rtn;
	}

	/**
	 * ユーザーが所属するプロジェクトの一覧を得る
	 */
	static public function get_user_projects( $user_id ){
		$relation = UserProjectRelation
			::where(['user_id'=>$user_id])
			->leftJoin('projects', 'user_project_relations.project_id', '=', 'projects.id')
			->get();

		return $relation;
	}

	/**
	 * グループが管理するプロジェクトの一覧を得る
	 */
	static public function get_group_projects( $group_id ){
		$projects = self
			::where(['group_id'=>$group_id])
			->orderBy('projects.name')
			->get();

		return $projects;
	}

}
