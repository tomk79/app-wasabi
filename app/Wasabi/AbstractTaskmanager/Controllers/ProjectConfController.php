<?php

namespace App\Wasabi\AbstractTaskmanager\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\User;
use App\Project;
use App\Wasabi\AbstractTaskmanager\Models\WasabiappAbstractTaskmanagerProjectConf;
use App\Wasabi\AbstractTaskmanager\Taskmanager;

class ProjectConfController extends \App\Http\Controllers\Controller
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
	public function edit(Request $request, $project_id, $app_id)
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


		\App\Helpers\wasabiHelper::push_breadclumb('Task Manager', '/pj/'.urlencode($project->id).'/app/AbstractTaskmanager');
		\App\Helpers\wasabiHelper::push_breadclumb('編集');

		$rtn = view(
			'App\Wasabi\AbstractTaskmanager::project_conf/edit',
			[
				'project'=>$project,
				'pjconf'=>$pjconf,
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

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function save(Request $request, $project_id, $app_id)
	{

		$request->validate([
			'foreign_service_id' => [
				'required',
				'string',
				'max:255',
				function ($attribute, $value, $fail) {
					switch ($value) {
						case 'backlog':
							break;
						default:
							$fail($attribute.' is invalid.');
							break;
					}
				},
			],
			'space' => [
				'string',
				'max:255',
				function ($attribute, $value, $fail) use ($request){
					if($request->foreign_service_id == 'backlog'){
						$parsed_url = parse_url($value);
						if( !preg_match('/^[a-zA-Z0-9\-\_]*\.backlog\.(?:jp|com)$/s', $parsed_url['host']) ){
							$fail($attribute.' is invalid.');
							return;
						}
						if ($parsed_url['scheme'] !== 'https') {
							$fail($attribute.' is invalid.');
						}
					}
				},
			],
			'foreign_project_id' => [
				'string',
				'max:255',
			],
		]);


		$project = Project::find($project_id)->first();
		$pjconf = WasabiappAbstractTaskmanagerProjectConf::where(['project_id'=>$project->id])->first();
		if( !$pjconf ){
			$tmp_pjconf = new WasabiappAbstractTaskmanagerProjectConf();
			$tmp_pjconf->project_id = $project->id;
			$tmp_pjconf->foreign_service_id = '';
			$tmp_pjconf->space = '';
			$tmp_pjconf->foreign_project_id = '';
			$tmp_pjconf->save();
			$pjconf = WasabiappAbstractTaskmanagerProjectConf::where(['project_id'=>$project->id])->first();
		}
		$taskmanager = new Taskmanager($project->id);

		$pjconf->project_id = $project->id;
		$pjconf->foreign_service_id = $request->foreign_service_id;
		$pjconf->space = $request->space;
		$pjconf->foreign_project_id = $request->foreign_project_id;
		$pjconf->save();

		return redirect('pj/'.urlencode($project->id).'/app/AbstractTaskmanager')->with('flash_message', 'プロジェクト情報を保存しました。');
	}

}
