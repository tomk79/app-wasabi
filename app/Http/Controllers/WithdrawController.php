<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    }

    /**
     * 退会の意思確認
     */
    public function confirm()
    {
        $user = Auth::user();
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
