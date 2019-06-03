<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Account;
use App\Rules\ReservedAccount;

class StoreUser extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		// 認可は別の箇所で行うので、ここでは素通りさせる
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules($id = null)
	{
		return [
			'name' => 'required|string|max:255',
			'account' => [
				'nullable',
				'string',
				'max:255',
				new Account,
				new ReservedAccount,
				'unique:users,account,'.$id,
			],
			'email' => [
				'required',
				'string',
				'email',
				'max:255',
				'unique:users,email,'.$id.'',
				'unique:user_sub_emails,email',
			],
			'password' => 'required|string|min:6|max:255|confirmed',
			'icon' => [
				'file',
				'mimetypes:image/png,image/jpeg,image/gif',
				'max:200',
			],
		];
	}
}
