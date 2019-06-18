<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\User;
use App\Http\Requests\StoreUser;

class WithdrawController extends Controller
{
    /**
     * 各アクションの前に実行させるミドルウェア
     */
    public function __construct()
    {
        $this->middleware('auth');

		// ナビゲーション制御
		View::share('current', "withdraw");
    }

    /**
     * 退会の意思確認
     */
    public function confirm()
    {
        $user = Auth::user();
		\helpers\wasabiHelper::push_breadclumb('プロフィール', '/settings/profile');
		\helpers\wasabiHelper::push_breadclumb('退会');
        return view('withdraw.confirm', ['profile' => $user]);
    }

    /**
     * 退会処理: 実行
     */
    public function withdraw()
    {
        $user = Auth::user();
        $user->delete();
        return redirect('/settings/withdraw/completed');
    }
}
