<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// Import models
use App\User;
use App\Projects;
use App\relayUsersXProjects;

class ProjectMemberController extends Controller
{

    /**
     * @param ProjectMember $project
     */
    public function __construct(User $user, Projects $project, relayUsersXProjects $relay_users_x_projects)
    {
        $this->middleware('auth'); // 認証が必要
        $this->me = \Auth::user(); // ログインユーザー
        $this->user = $user;
        $this->project = $project;
        $this->relay_users_x_projects = $relay_users_x_projects;
    }

    public function create(Request $request)
    {
        $data = $request->all();
        $project = $this->project
            ->find($data['project_id']);

        return view('project/member/create')
            ->with( compact('project') )
        ;
    }

    public function store(Request $request)
    {
        // var_dump('---- store() ----');
        $data = $request->all();

        $this->validate($request, [
            'project_id' => 'required|integer|exists:projects,id',
            'email' => 'required|email|exists:users,email',
            'authority' => 'required|integer|min:0|max:1024',
        ]);

        $user = $this->user
            ->where('email', $data['email'])
            ->first();

        // 一旦 delete
        $this->relay_users_x_projects
            ->where('user_id', $user->id)
            ->where('project_id', $data['project_id'])
            ->delete();

        // insert relay table
        $this->relay_users_x_projects->insert(array(
            'user_id'=>$user->id,
            'project_id'=>$data['project_id'],
            'authority'=>$data['authority'],
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ));

        // $this->project->fill($data);
        // $this->project->save();
        return redirect()->to('project/'.$data['project_id']);
    }

    public function destroy(Request $request)
    {
        // var_dump('---- destroy('.$id.') ----');
        $data = $request->all();

        $this->validate($request, [
            'user_id' => 'required|integer|exists:users,id',
            'project_id' => 'required|integer|exists:projects,id',
        ]);

        $this->relay_users_x_projects
            ->where('user_id', $data['user_id'])
            ->where('project_id', $data['project_id'])
            ->delete()
        ;
        return redirect()->to('project/'.$data['project_id']);
    }

}
