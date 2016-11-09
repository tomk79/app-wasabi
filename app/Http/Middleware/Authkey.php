<?php

namespace App\Http\Middleware;

use Closure;

// Import models
use App\User;
use App\UserApiKey;

class Authkey
{

    /** @var UserApiKey */
    protected $user_api_key;

    /** @var User */
    protected $user;

    /**
     * @param UserApiKey $project
     */
    public function __construct(User $user, UserApiKey $user_api_key)
    {
        $this->user = $user;
        $this->user_api_key = $user_api_key;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // authkeyをgetパラメータで受け取る。
        // 一度認証が確認できたら、ユーザーの情報はセッションに記憶する。
        $authkey = $request->input('authkey');

        if( $authkey ){

            $authuser_relay = $this->user_api_key
                ->where('authkey', $authkey)
                ->first()
            ;
            if( !is_null( $authuser_relay ) ){
                $me = $this->user
                    ->find($authuser_relay->user_id)
                ;
            }

            if( @$me->id ){
                // ログイン成功
                $request->session()->put('myself', array(
                    'id'=>$me->id,
                    'name'=>$me->name,
                    'email'=>$me->email,
                ));
                $request->session()->put('authkey', $authkey);
            }else{
                // ログイン失敗
                $request->session()->put('myself', false);
                $request->session()->put('authkey', null);
            }

        }else{
            // 認証済み authkey の死活確認
            // ログイン後、authkey はセッションに記憶される。
            // ログイン中に、ユーザーが authkey を削除したら、ログイン状態を解除しなければならない。
            $authuser_relay = $this->user_api_key
                ->where('authkey', $request->session()->get('authkey'))
                ->first()
            ;
            if( is_null( $authuser_relay ) ){
                // ログイン失敗
                $request->session()->put('myself', false);
                $request->session()->put('authkey', null);
            }

        }

        $myself = $request->session()->get('myself');
        if( !$myself ){
            return response()->json( array('message'=>"Unauthorized.") , 401);
        }
        return $next($request);
    }
}
