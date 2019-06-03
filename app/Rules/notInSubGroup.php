<?php

namespace App\Rules;

use App\Group;
use Illuminate\Contracts\Validation\Rule;

class notInSubGroup implements Rule
{
	private $before = null;

	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct($before)
	{
		$this->before = $before;
	}

	/**
	 * notInSubGroupをバリデート
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
        $sub_groups = Group::get_sub_groups($this->before);
        foreach( $sub_groups as $sub_group ){
			if( $sub_group['group']->id == $value ){
				return false;
			}
        }
		return true;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return 'This group contains itself.';
	}
}
