<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserSubEmail;

class StartpageController extends Controller
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	/**
	 * はじめの画面
	 * ログイン時はダッシュボードを表示する。
	 */
	public function startpage(){
		$user = Auth::user();
		if( !$user ){
			return view('startpage.index');
		}

		$subEmails = UserSubEmail::where(['user_id'=>$user->id])->get();

		return view('startpage.dashboard', ['profile' => $user, 'sub_emails'=>$subEmails]);
	}
}
