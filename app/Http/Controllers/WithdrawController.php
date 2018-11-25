<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class WithdrawController extends Controller
{

    /** @var Login User */
    protected $me;

    /**
     * @param Project $project
     */
    public function __construct(){
        $this->middleware('auth'); // 認証が必要
        $this->me = \Auth::user();
    }

    /**
     * 退会: トップページ
     */
    public function confirm(){
        return view('withdraw/confirm')
            ->with( array(
                'profile' => $this->me,
            ) )
        ;
    }

    /**
     * 退会処理
     */
    public function withdraw(){
        $this->me->delete();
        return redirect()->to('/withdraw/completed');
    }

}
