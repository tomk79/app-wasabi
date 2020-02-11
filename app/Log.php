<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserProjectRelation;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;

class Log extends Model
{
	/** プライマリーキーの型 */
	protected $keyType = 'string';

	/** プライマリーキーは自動連番か？ */
	public $incrementing = false;

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
