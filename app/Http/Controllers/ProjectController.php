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
        var_dump($request);
        $data = $request->all();
        var_dump($data);
        // $this->project->fill($data);
        // $this->project->save();

        return redirect()->to('project');
        // $projects = $this->project->all();
        // var_dump($projects);
        // var_dump(compact('projects'));
        // return view('project/index')->with( compact('projects') );
    }

    public function store()
    {
        //
    }

    public function show($id)
    {
        $projects = $this->project->find($id);

        return view('project/show', compact('projects'));
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

    public function update($id)
    {
        //
    }

    public function destroy($id)
    {
        $project = $this->project->find($id);
        $project->delete();
        return redirect()->to('project');
    }
}
