<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use App\UserGroupRelation;
use Illuminate\Support\Facades\Auth;

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
	 * 
	 * ルートプロジェクトを先頭に置き、自身を含んだ配列を返します。
	 * 権限の評価は行いません。
	 */
	static public function get_logical_path( $group_id ){

		$rtn = array();
		$current_group_id = $group_id;
		while( 1 ){
			if( count($rtn) >= 20 ){
				break;
			}

			$current_group = Group::find($current_group_id);
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
	 * 子グループの一覧を取得する
	 * 
	 * 権限の評価は行いません。
	 */
	static public function get_children( $group_id ){
		$rtn = self::where(['parent_group_id'=>$group_id])->get();
		return $rtn;
	}

	/**
	 * 兄弟グループの一覧を取得する
	 * 
	 * 権限の評価は行いません。
	 */
	static public function get_bros( $group_id ){
		$group = self::find($group_id);
		if( !strlen($group->parent_group_id) || !strlen($group->root_group_id) ){
			return array();
		}
		$rtn = self::get_children( $group->id );
		return $rtn;
	}

	/**
	 * 子グループ以下すべてのグループを取得する
	 * 
	 * 権限の評価は行いません。
	 */
	static public function get_sub_groups( $group_id, $parentRow = null ){
		$rtn = array();
		$children = self::get_children( $group_id );
		if( $parentRow && $parentRow['depth'] > 20 ){
			return array();
		}
		foreach($children as $child){
			$tmpChildRow = array(
				'fullname' => ($parentRow ? $parentRow['fullname'].'>' : '' ).$child->name,
				'group' => $child,
				'depth' => ($parentRow ? $parentRow['depth'] + 1 : 0)
			);
			array_push($rtn, $tmpChildRow);
			$subchildren = self::get_sub_groups($child->id, $tmpChildRow);
			$rtn = array_merge($rtn, $subchildren);
		}
		return $rtn;
	}

	/**
	 * グループに対するユーザーの権限を取得する
	 * 
	 * グループの階層構造を、最上位から下へ向かって検証します。
	 * はじめにユーザーが所属するグループ(=ユーザーが所属する最上位)に割り当てられた権限が、
	 * 下位全部のグループに適用されます。
	 * 
	 * ユーザーの role が発見できない場合(=どの層にも所属していない場合)、
	 * false を返します。
	 */
	static public function get_user_permissions( $group_id, $user_id = null ){
		$rtn = array(
			'role' => false,
			'has_sub_group_membership' => false,
			'has_sub_group_partnership' => false,
			'has_sub_group_observership' => false,
			'editable' => false,
		);
		if( !strlen($user_id) ){
			$user = Auth::user();
			$user_id = $user->id;
		}
		$logical_path = self::get_logical_path($group_id);
		foreach($logical_path as $tmp_group){
			$relation = UserGroupRelation
				::where(['group_id'=>$tmp_group->id, 'user_id'=>$user_id])
				->leftJoin('users', 'user_group_relations.user_id', '=', 'users.id')
				->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
				->first();
			if(!$relation){
				continue;
			}
			$rtn['role'] = $relation->role;
			switch($rtn['role']){
				case 'owner':
				case 'manager':
					$rtn['editable'] = true;
					break;
			}
			break;
		}

		$sub_groups = self::get_sub_groups($logical_path[0]->id);
		foreach($sub_groups as $tmp_group){
			$relation = UserGroupRelation
				::where(['group_id'=>$tmp_group['group']->id, 'user_id'=>$user_id])
				->leftJoin('users', 'user_group_relations.user_id', '=', 'users.id')
				->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
				->first();
			if(!$relation){
				continue;
			}
			switch( $relation->role ){
				case 'owner':
				case 'manager':
				case 'member':
					$rtn['has_sub_group_membership'] = true;
					break;
				case 'partner':
					$rtn['has_sub_group_partnership'] = true;
					break;
				case 'observer':
					$rtn['has_sub_group_observership'] = true;
					break;
				default:
					break;
			}
			break;
		}

		if( !$rtn['role'] && !$rtn['has_sub_group_membership'] && !$rtn['has_sub_group_partnership'] && !$rtn['has_sub_group_observership'] ){
			$rtn = false;
		}
		return $rtn;
	}

	/**
	 * ユーザーが所属するグループの一覧を得る
	 */
	static public function get_user_groups( $user_id ){
		$relation = UserGroupRelation
			::where(['user_id'=>$user_id])
			->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
			->get();

		return $relation;
	}

}
