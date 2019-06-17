<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')
	->group(function(){

		Route::get('/user', function (Request $request) {
			return $request->user();
		});

		Route::get('/user_info', function (Request $request) {
			$rtn = array();
			$rtn['user'] = $request->user();
			$rtn['groups'] = \App\Group::get_user_groups( $rtn['user']->id );
			$rtn['projects'] = \App\Project::get_user_projects( $rtn['user']->id );
			return $rtn;
		});

	})
;
