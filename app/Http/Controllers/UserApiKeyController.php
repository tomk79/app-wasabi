<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// Import models
use App\User;
use App\UserApiKey;

class UserApiKeyController extends Controller
{

    /** @var UserApiKey */
    protected $user_api_key;

    /** @var User */
    protected $user;

    /** @var Login User */
    protected $me;

    /**
     * @param UserApiKey $project
     */
    public function __construct(User $user, UserApiKey $user_api_key)
    {
        $this->middleware('auth'); // 認証が必要
        $this->me = \Auth::user(); // ログインユーザー
        $this->user = $user;
        $this->user_api_key = $user_api_key;
    }

    public function index()
    {
        $user_api_keys = $this->user_api_key
            ->where('user_id', $this->me->id)
            ->orderBy('created_at')
            ->get();

        return view('user_api_keys/index')
            ->with( compact('user_api_keys') )
        ;
    }

    public function create(Request $request)
    {
        $data = $request->all();
        $me = $this->me;

        return view('user_api_keys/create')
            ->with( compact('me') )
        ;
    }

    public function store(Request $request)
    {
        // var_dump('---- store() ----');
        $data = $request->all();

        $this->validate($request, [
            'name' => 'required|string',
        ]);

        // insert relay table
        $hash = $this->generate_random_hash();
        $authkey = $this->generate_random_hash().$this->generate_random_hash();
        $this->user_api_key->insert(array(
            'hash'=>$hash,
            'user_id'=>$this->me->id,
            'name'=>$data['name'],
            'authkey'=>$authkey,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ));

        // $this->project->fill($data);
        // $this->project->save();
        return response()->json(array(
            'authkey' => $authkey,
        ));
    }

    public function destroy($hash, Request $request)
    {
        // var_dump('---- destroy('.$id.') ----');
        $data = $request->all();

        $this->user_api_key
            ->where('user_id', $this->me->id)
            ->where('hash', $hash)
            ->delete()
        ;
        return redirect()->to('userApiKey');
    }

    private function generate_random_hash(){
        $rtn = rand(0,40000).'-'.microtime();
        $rtn = md5($rtn);
        return $rtn;
    }

}
