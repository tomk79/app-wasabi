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
        $projects = $this->project->all();
        // var_dump($projects);
        // var_dump(compact('projects'));
        return view('project/index')
            ->with( compact('projects') )
        ;
    }

    public function create(Request $request)
    {
        // var_dump($request);
        // var_dump($request->all());
        // var_dump($request->path());
        // var_dump($request->url());
        // var_dump($request->fullUrl());
        // var_dump($request->input('name', 'default')); // 入力値 (第2引数はデフォルト値)
        // var_dump($request->isMethod('post'));
        // var_dump($request->isMethod('get'));

        $projects = $this->project->all();
        // var_dump($projects);
        // var_dump(compact('projects'));
        return view('project/create')
            ->with( compact('projects') )
        ;
    }

    public function store()
    {
        var_dump('---- store() ----');
    }

    public function show($id)
    {
        // var_dump('---- show('.$id.') ----');
        $project = $this->project->find($id);
        // var_dump($project);

        return view('project/show', compact('project'));
    }

    public function edit($id)
    {
        $project = $this->project->find($id);

        return view('project/edit')->withProject($project);
    }

    public function postEdit($id)
    {
        $project = $this->project->find($id);
        $data = $request->all();
        $project->fill($data);
        $project->save();

        return redirect()->to('project');
    }

    public function update($id, Request $request)
    {
        // var_dump('---- update('.$id.') ----');
        $data = $request->all();
        // var_dump($data);
        $this->project->fill($data);
        $this->project->save();
        return redirect()->to('project');
    }

    public function destroy($id)
    {
        $project = $this->project->find($id);
        $project->delete();
        return redirect()->to('project');
    }
}
