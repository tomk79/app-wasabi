<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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

    /**
     * プロフィール: トップページ
     */
    public function index(){
        return view('profile/index')
            ->with( array(
                'profile' => $this->me,
            ) )
        ;
    }

    /**
     * プロフィール: 編集ページ
     */
    public function edit(){
        return view('profile/edit')
            ->with( array(
                'profile' => $this->me,
            ) )
        ;
    }

    /**
     * プロフィール: 編集からの更新処理
     */
    public function update(Request $request){
        $data = $request->all();

        $this->validate($request, [
            'name' => 'required|max:255',
            'account' => 'required|max:255',
            'email' => 'required|email',
            'password' => 'min:4|max:1024',
        ]);

        $this->me
            ->update(array(
                'name'=>$data['name'],
                'account'=>$data['account'],
                'email'=>$data['email'],
            ))
        ;
        if( strlen($data['password']) ){
            $this->me
                ->update(array(
                    'password'=>bcrypt($data['password']),
                ))
            ;
        }
        return redirect()->to('profile');
    }

}
