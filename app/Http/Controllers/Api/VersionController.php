<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class VersionController extends Controller
{

    public function index()
    {
        $version = \Config::get('custom.version');
        return response()->json($version);
    }

}
