<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
	use Notifiable;
	use SoftDeletes;
	use HasApiTokens;

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
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'account', 'email', 'lang', 'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * Constructor
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

		// newした時に自動的にuuidを設定する。
		// DBにすでに存在するレコードをロードする場合は、あとから上書きされる。
		$this->attributes['id'] = Uuid::uuid4()->toString();
	}
}
