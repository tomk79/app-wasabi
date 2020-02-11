<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

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
		$this->attributes['via'] = 'web';
		if( array_key_exists('REMOTE_ADDR', $_SERVER) ){
			$this->attributes['ip_address'] = $_SERVER['REMOTE_ADDR'];
		}
		if( array_key_exists('REQUEST_METHOD', $_SERVER) ){
			$this->attributes['http_method'] = $_SERVER['REQUEST_METHOD'];
		}
		if( array_key_exists('HTTP_USER_AGENT', $_SERVER) ){
			$this->attributes['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		}
		if( array_key_exists('REQUEST_URI', $_SERVER) ){
			$this->attributes['request_uri'] = $_SERVER['REQUEST_URI'];
		}
	}

}
