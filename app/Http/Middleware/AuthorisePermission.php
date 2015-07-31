<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AuthorisePermission
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
        $permissions = array_slice(func_get_args(), 2);

        if(!Auth::check() || !Auth::user()->can($permissions, false)) {
            if($request->ajax()) {
                return response('Unauthorized.', 401);
            } else if(!Auth::check()) {
                return redirect()->guest('login');
            } else {
                App::abort(401);
            }
        }

        return $next($request);
    }
}
