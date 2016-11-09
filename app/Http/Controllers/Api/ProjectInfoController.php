<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// Import models
use App\Projects;
use App\ProjectMembers;

class ProjectInfoController extends Controller
{

    /**
     */
    public function __construct(Projects $project, ProjectMembers $project_members)
    {
        $this->middleware('authkey'); // authkey認証が必要
        $this->project = $project;
        $this->project_members = $project_members;
    }

    /**
     */
    public function index( $project_account, Request $request )
    {
        $project = $this->project
            ->where('account', $project_account)
            ->first()
        ;
        if( is_null( $project ) ){
            return response()->json( array('message'=>"Not found.") , 404);
        }

        $myself = $request->session()->get('myself');
        $member = $this->project_members
            ->where('project_id', $project->id)
            ->where('user_id', $myself['id'])
            ->first()
        ;
        if( is_null( $member ) ){
            return response()->json( array('message'=>"Not found.") , 404);
        }

        return response()->json($project);
    }

}
