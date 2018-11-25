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

class ProjectController extends Controller
{

    /** @var Project */
    protected $project;

    /** @var ProjectMembers */
    protected $project_members;

    /** @var User */
    protected $user;

    /** @var Login User */
    protected $me;

    /**
     * @param Project $project
     */
    public function __construct(User $user, Projects $project, ProjectMembers $project_members)
    {
        $this->middleware('auth'); // 認証が必要
        $this->me = \Auth::user();
        $this->user = $user;
        $this->project = $project;
        $this->project_members = $project_members;
    }

    public function index()
    {
        $projects = $this->project
            ->where('user_id', $this->me->id)
            ->orderBy('account')
            ->get();

        return view('project/index')
            ->with( compact('projects') )
        ;
    }

    public function create(Request $request)
    {
        return view('project/create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $this->validate($request, [
            'name' => 'required|max:255',
            'account' => 'required|min:4|max:1024|unique:projects,account',
        ]);

        $id = $this->project->insertGetId(array(
            'user_id'=>$this->me->id,//ログインユーザーのID
            'name'=>$data['name'],
            'account'=>$data['account'],
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ));

        // insert relay table
        $this->project_members->insert(array(
            'user_id'=>$this->me->id,
            'project_id'=>$id,
            'authority'=>10,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ));

        return redirect()
            ->to('project')
            ->with( array(
                'flash_message' => 'プロジェクトを作成しました。'
            ) )
        ;
    }

    public function show($account)
    {
        $project = $this->project
            ->where('account', $account)
            ->where('user_id', $this->me->id)
            ->first()
        ;

        $owner_user = $this->user
            ->find($project->user_id)
        ;

        $members = $this->project_members
            ->leftJoin('users', 'project_members.user_id', '=', 'users.id')
            ->where('project_id', $project['id'])
            ->orderBy('project_members.authority', 'users.email')
            ->get();

        return view('project/show', compact('project', 'owner_user', 'members'));
    }

    public function edit($account)
    {
        // var_dump('---- edit('.$id.') ----');
        $project = $this->project
            ->where('account', $account)
            ->where('user_id', $this->me->id)
            ->first()
        ;

        return view('project/edit', compact('project'));
    }

    public function update($account, Request $request)
    {
        // var_dump('---- update('.$id.') ----');
        $data = $request->all();

        $project = $this->project
            ->where('account', $account)
            ->where('user_id', $this->me->id)
            ->first()
        ;

        $this->validate($request, [
            'name' => 'required|max:255',
            'account' => 'required|min:4|max:1024|unique:projects,account,'.$project->id,
        ]);

        $this->project
            ->where('account', $account)
            ->where('user_id', $this->me->id)
            ->update(array(
                'name'=>$data['name'],
                'account'=>$data['account'],
            ))
        ;
        return redirect()
            ->to('project/'.$data['account'])
            ->with( array(
                'flash_message' => 'プロジェクトを更新しました。'
            ) )
        ;
    }

    public function destroy($account)
    {
        // var_dump('---- destroy('.$id.') ----');
        $project = $this->project
            ->where('account', $account)
            ->where('user_id', $this->me->id)
            ->first()
        ;
        $project->delete();
        return redirect()->to('project');
    }
}
