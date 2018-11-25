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

class ProfileController extends Controller
{

    /** @var Login User */
    protected $me;

    /**
     * @param Project $project
     */
    public function __construct(){
        $this->middleware('auth'); // 認証が必要
        $this->me = \Auth::user();
    }

    public function index(){
        return view('profile/index')
            ->with( array(
                'profile' => $this->me,
            ) )
        ;
    }

    // public function edit($account){
    //     // var_dump('---- edit('.$id.') ----');
    //     $project = $this->project
    //         ->where('account', $account)
    //         ->where('user_id', $this->me->id)
    //         ->first()
    //     ;

    //     return view('project/edit', compact('project'));
    // }

    // public function update($account, Request $request){
    //     // var_dump('---- update('.$id.') ----');
    //     $data = $request->all();

    //     $project = $this->project
    //         ->where('account', $account)
    //         ->where('user_id', $this->me->id)
    //         ->first()
    //     ;

    //     $this->validate($request, [
    //         'name' => 'required|max:255',
    //         'account' => 'required|min:4|max:1024|unique:projects,account,'.$project->id,
    //     ]);

    //     $this->project
    //         ->where('account', $account)
    //         ->where('user_id', $this->me->id)
    //         ->update(array(
    //             'name'=>$data['name'],
    //             'account'=>$data['account'],
    //         ))
    //     ;
    //     return redirect()->to('project/'.$data['account']);
    // }

    // public function destroy($account){
    //     // var_dump('---- destroy('.$id.') ----');
    //     $project = $this->project
    //         ->where('account', $account)
    //         ->where('user_id', $this->me->id)
    //         ->first()
    //     ;
    //     $project->delete();
    //     return redirect()->to('project');
    // }

}
