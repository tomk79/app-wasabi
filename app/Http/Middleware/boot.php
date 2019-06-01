<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
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

		return $next($request);
	}
}
