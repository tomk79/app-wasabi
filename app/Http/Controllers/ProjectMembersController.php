<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Group;
use App\User;
use App\Project;
use App\UserProjectRelation;
use App\Http\Requests\StoreGroup;
use App\Rules\Role;

class ProjectMembersController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($project_id)
	{
		$user = Auth::user();

		$user_permissions = Project::get_user_permissions($project_id, $user->id);
		if( $user_permissions === false ){
			// ユーザーは所属していない
			return abort(404);
		}

		$project = Project::find($project_id);
		if( !$project ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		$members = UserProjectRelation
			::where(['project_id'=>$project->id])
			->leftJoin('users', 'user_project_relations.user_id', '=', 'users.id')
			->orderBy('email')
			->paginate(100);

		return view('projectmembers.index', ['project'=>$project, 'members'=>$members, 'profile' => $user]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($project_id)
	{
		$user = Auth::user();

		$user_permissions = Project::get_user_permissions($project_id, $user->id);
		if( $user_permissions === false ){
			// ユーザーは所属していない
			return abort(404);
		}
		if( !$user_permissions['editable'] ){
			// 権限がありません
			return abort(403, 'このグループを編集する権限がありません。');
		}

		return view('projectmembers.create', ['project_id'=>$project_id, 'profile' => $user]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store($project_id, Request $request)
	{
		$user = Auth::user();

		$user_permissions = Project::get_user_permissions($project_id, $user->id);
		if( $user_permissions === false ){
			// ユーザーは所属していない
			return abort(404);
		}
		if( !$user_permissions['editable'] ){
			// 権限がありません
			return abort(403, 'このグループを編集する権限がありません。');
		}

		$project = Project::find($project_id);
		if( !$project ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}
		$invited_user = User
			::where('email', $request->email)
			->first();
		$invited_user_id = null;
		if( $invited_user ){
			$invited_user_id = $invited_user->id;
		}

		$request->validate([
			'email' => [
				'required',
				'exists:users',
				function ($attribute, $value, $fail) use ($invited_user_id, $user){
					if( $invited_user_id == $user->id ){
						$fail('自分を招待することはできません。');
					}
				},
			],
			'role' => [
				'required',
				new Role,
			],
		]);

		$userProjectRelation = new UserProjectRelation;
		$userProjectRelation->user_id = $invited_user_id;
		$userProjectRelation->project_id = $project->id;
		$userProjectRelation->role = $request->role;
		$userProjectRelation->save();

		return redirect('settings/projects/'.urlencode($project_id).'/members')->with('flash_message', '新しいメンバー '.$request->email.' を招待しました。');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($project_id, $email, Request $request)
	{
		$user = Auth::user();

		$user_permissions = Project::get_user_permissions($project_id, $user->id);
		if( $user_permissions === false ){
			// ユーザーは所属していない
			return abort(404);
		}

		$project = Project::find($project_id);
		if( !$project ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		$invited_user = User
			::where('email', $email)
			->first();
		if( !$invited_user->count() ){
			// 条件に合うレコードが存在しない場合
			return abort(403);
		}
		$invited_user_id = $invited_user->id;

		$relation = $userProjectRelation = UserProjectRelation
			::where('user_id', $invited_user_id)
			->where('project_id', $project->id)
			->first();
		if( !$relation->count() ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}
		return view('projectmembers.show', ['relation'=>$relation, 'project'=>$project, 'user' => $invited_user, 'profile' => $user]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($project_id, $email, Request $request)
	{
		$user = Auth::user();

		$user_permissions = Project::get_user_permissions($project_id, $user->id);
		if( $user_permissions === false ){
			// ユーザーは所属していない
			return abort(404);
		}
		if( !$user_permissions['editable'] ){
			// 権限がありません
			return abort(403, 'このグループを編集する権限がありません。');
		}

		$project = Project::find($project_id);
		if( !$project ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		$invited_user = User
			::where('email', $email)
			->first();
		if( !$invited_user->count() ){
			// 条件に合うレコードが存在しない場合
			return abort(403);
		}
		$invited_user_id = $invited_user->id;
		if( $invited_user_id == $user->id ){
			// 自分を編集することはできない
			return abort(403, '自分を編集することはできません。');
		}

		$relation = $userProjectRelation = UserProjectRelation
			::where('user_id', $invited_user_id)
			->where('project_id', $project->id)
			->first();
		if( !$relation->count() ){
			// 条件に合うレコードが存在しない場合
			// = ログインユーザー自身が指定のグループに参加していない。
			return abort(404);
		}
		return view('projectmembers.edit', ['relation'=>$relation, 'project'=>$project, 'user' => $invited_user, 'profile' => $user]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update($project_id, $email, Request $request)
	{
		$user = Auth::user();

		$user_permissions = Project::get_user_permissions($project_id, $user->id);
		if( $user_permissions === false ){
			// ユーザーは所属していない
			return abort(404);
		}
		if( !$user_permissions['editable'] ){
			// 権限がありません
			return abort(403, 'このグループを編集する権限がありません。');
		}

		$project = Project::find($project_id);
		if( !$project ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		$invited_user = User
			::where('email', $email)
			->first();
		if( !$invited_user->count() ){
			// 条件に合うレコードが存在しない場合
			return abort(403);
		}
		$invited_user_id = $invited_user->id;
		if( $invited_user_id == $user->id ){
			// 自分を編集することはできない
			return abort(403, '自分を編集することはできません。');
		}

		$request->validate([
			'role' => [
				'required',
				new Role,
			],
		]);

		DB::table('user_project_relations')
			->where('user_id', $invited_user_id)
			->where('project_id', $project->id)
			->update(['role' => $request->role]);

		return redirect('settings/projects/'.urlencode($project->id).'/members')->with('flash_message', 'メンバー '.$invited_user->email.' の情報を更新しました。');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($project_id, $email, Request $request)
	{
		$user = Auth::user();

		$user_permissions = Project::get_user_permissions($project_id, $user->id);
		if( $user_permissions === false ){
			// ユーザーは所属していない
			return abort(404);
		}
		if( !$user_permissions['editable'] ){
			// 権限がありません
			return abort(403, 'このグループを編集する権限がありません。');
		}

		$project = Project::find($project_id);
		if( !$project ){
			// 条件に合うレコードが存在しない場合
			return abort(404);
		}

		$invited_user = User
			::where('email', $email)
			->first();
		if( !$invited_user->count() ){
			// 条件に合うレコードが存在しない場合
			return abort(403);
		}
		$user_id = $invited_user->id;
		if( $user_id == $user->id ){
			// 自分を除名することはできない
			return abort(403);
		}

		$userProjectRelation = UserProjectRelation
			::where(['user_id' => $user_id, 'project_id' => $project_id])
			->delete();

		return redirect('settings/projects/'.urlencode($project_id).'/members')->with('flash_message', 'メンバー '.$email.' をメンバーから外しました。');
	}
}
