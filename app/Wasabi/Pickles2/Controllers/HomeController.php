<?php

namespace App\Wasabi\Pickles2\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\User;
use App\Project;
use App\Wasabi\AbstractTaskmanager\Taskmanager;

class HomeController extends \App\Http\Controllers\Controller
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// 各アクションの前に実行させるミドルウェア
		$this->middleware('auth');

		// ナビゲーション制御
		View::share('current', "projects");
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $project_id, $app_id, $params = null)
	{
		$taskmanager = new Taskmanager($project_id);
		ob_start();
		var_dump($project_id, $app_id);
		var_dump($taskmanager->get_foreign_user_info());
		// var_dump($taskmanager->get_foreign_space_info());
		// var_dump($taskmanager->get_foreign_project_info());
		var_dump($taskmanager->get_ticket_list());
		$fin = ob_get_clean();

		\App\Helpers\wasabiHelper::push_breadclumb('Pickles 2');

		$rtn = view(
			'App\Wasabi\Pickles2::index',
			['main'=>$fin]
		);
		return view(
			'projects.app.index',
			[
				'app_id'=>$app_id,
				'app_name'=>'Pickles 2',
				'main'=>$rtn,
			]
		);
	}

}
