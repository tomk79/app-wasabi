<?php

namespace App\Wasabi\AbstractTaskmanager\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\User;
use App\Project;
use App\Wasabi\AbstractTaskmanager\Models\WasabiappAbstractTaskmanagerProjectConf;
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
		$project = Project::find($project_id)->first();
		$pjconf = WasabiappAbstractTaskmanagerProjectConf::where(['project_id'=>$project_id])->first();
		if( !$pjconf ){
			$pjconf = json_decode('{}');
			$pjconf->foreign_service_id = '';
			$pjconf->space = '';
			$pjconf->foreign_project_id = '';
		}
		$taskmanager = new Taskmanager($project_id);
		$user_info = $taskmanager->get_foreign_user_info();

		\App\Helpers\wasabiHelper::push_breadclumb('Task Manager');

		ob_start();
		var_dump(json_decode($user_info));
		var_dump($project_id, $params);
		$fin = ob_get_clean();

		$rtn = view(
			'App\Wasabi\AbstractTaskmanager::index',
			[
				'project'=>$project,
				'pjconf'=>$pjconf,
				'main'=>$fin,
			]
		);

		return view(
			'projects.app.index',
			[
				'app_id'=>$app_id,
				'app_name'=>'Task Manager',
				'main'=>$rtn,
			]
		);
	}

}
