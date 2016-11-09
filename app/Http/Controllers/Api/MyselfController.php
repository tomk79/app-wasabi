<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class MyselfController extends Controller
{

    /** @var Login User */
    protected $me;

    /**
     * @param UserApiKey $project
     */
    public function __construct()
    {
        $this->middleware('auth'); // 認証が必要
        $this->me = \Auth::user(); // ログインユーザー
    }

    public function index()
    {
        return response()->json($this->me);
    }

}
