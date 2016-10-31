<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Projects; // Projectsモデルをインポート

class ProjectController extends Controller
{

    /**
     * @var Project
     */
    protected $project;

    /**
     * @param Article $project
     */
    public function __construct(Projects $project)
    {
        $this->middleware('auth'); // 認証が必要
        $this->project = $project;
    }

    public function index()
    {
        $projects = $this->project
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
        // var_dump('---- store() ----');
        $data = $request->all();
        $this->project->fill($data);
        $this->project->save();
        return redirect()->to('project');
    }

    public function show($id)
    {
        $project = $this->project->find($id);
        return view('project/show', compact('project'));
    }

    public function edit($id)
    {
        // var_dump('---- edit('.$id.') ----');
        $project = $this->project->find($id);

        return view('project/edit', compact('project'));
    }

    public function update($id, Request $request)
    {
        // var_dump('---- update('.$id.') ----');
        $data = $request->all();
        $this->project
            ->find($id)
            ->update(array(
                'name'=>$data['name'],
                'account'=>$data['account'],
            ))
        ;
        return redirect()->to('project/'.$id);
    }

    public function destroy($id)
    {
        // var_dump('---- destroy('.$id.') ----');
        $project = $this->project->find($id);
        $project->delete();
        return redirect()->to('project');
    }
}
