<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Account;
use App\Rules\ReservedAccount;

class StoreGroup extends FormRequest
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
			'name' => ['required', 'string', 'max:255'],
			'account' => ['required', 'string', 'max:255', new Account, new ReservedAccount, 'unique:groups,account,'.$id],
			'description' => ['nullable', 'string'],
			'icon' => ['file', 'mimetypes:image/png,image/jpeg,image/gif', 'max:200'],
		];
	}
}
