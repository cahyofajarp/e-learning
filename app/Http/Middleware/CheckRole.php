<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next,...$roles)
    {
        
        if(in_array($request->user()->roles, $roles))
            {

                return $next($request);

            }
        return abort(403, "Cannot access to restricted page");

    }
}
