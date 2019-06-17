<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App;

class boot
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		App::setLocale('ja'); // default locale

		$global = new \stdClass;
		View::share('global', $global);

		$user = Auth::user();
		if( $user ){
			if( !$user->icon ){
				$user->icon = url('/common/images/nophoto.png');
			}
		}
		$global->user = $user;

		return $next($request);
	}
}
