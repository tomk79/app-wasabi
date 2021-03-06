<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Rules\Account;
use App\Rules\ReservedAccount;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

		// ナビゲーション制御
		View::share('current', "register");
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'account' => [
				'nullable',
				'string',
				'max:255',
				new Account,
				new ReservedAccount,
				'unique:users,account,',
			],
            'lang' => ['required', 'string', 'max:255'],
            'email' => [
				'required',
				'string',
				'email',
				'max:255',
				'unique:users,email,',
				'unique:user_sub_emails,email',
			],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'account' => $data['account'],
            'lang' => $data['lang'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
