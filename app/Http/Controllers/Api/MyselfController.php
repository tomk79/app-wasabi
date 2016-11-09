<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MyselfController extends Controller
{

    /** @var Login User */
    protected $me;

    /**
     */
    public function __construct()
    {
        $this->middleware('authkey'); // authkey認証が必要
    }

    /**
     */
    public function index( Request $request )
    {
        $myself = $request->session()->get('myself');
        return response()->json($myself);
    }

}
