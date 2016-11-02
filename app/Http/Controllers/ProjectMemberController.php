<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// Import models
use App\User;
use App\Projects;
use App\ProjectMembers;

class ProjectMemberController extends Controller
{

    /**
     * @param ProjectMember $project
     */
    public function __construct(User $user, Projects $project, ProjectMembers $project_members)
    {
        $this->middleware('auth'); // 認証が必要
        $this->me = \Auth::user(); // ログインユーザー
        $this->user = $user;
        $this->project = $project;
        $this->project_members = $project_members;
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

        if( $this->me->email == $data['email'] ){
            // 自分自身は書き換えられない
            return redirect()->to('project/'.$data['project_id']);
        }

        $member = $this->project_members
            ->where('user_id', $this->me->id)
            ->where('project_id', $data['project_id'])
            ->first();

        if( $member->authority < 10 ){
            // 権限がない場合は消せない
            return redirect()->to('project/'.$data['project_id']);
        }

        $user = $this->user
            ->where('email', $data['email'])
            ->first();

        // 一旦 delete
        $this->project_members
            ->where('user_id', $user->id)
            ->where('project_id', $data['project_id'])
            ->delete();

        // insert relay table
        $this->project_members->insert(array(
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

        if( $this->me->id == $data['user_id'] ){
            // 自分自身は消せない
            return redirect()->to('project/'.$data['project_id']);
        }

        $member = $this->project_members
            ->where('user_id', $this->me->id)
            ->where('project_id', $data['project_id'])
            ->first();

        if( $member->authority < 10 ){
            // 権限がない場合は消せない
            return redirect()->to('project/'.$data['project_id']);
        }

        $this->project_members
            ->where('user_id', $data['user_id'])
            ->where('project_id', $data['project_id'])
            ->delete()
        ;
        return redirect()->to('project/'.$data['project_id']);
    }

}
