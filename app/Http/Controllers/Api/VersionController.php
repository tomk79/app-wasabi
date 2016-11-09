<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class VersionController extends Controller
{

    public function index()
    {
        return response()->json("0.0.1-alpha.1+nb");
    }

}
