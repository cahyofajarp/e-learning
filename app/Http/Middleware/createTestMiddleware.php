<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class createTestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if($request->code != $request->lesson){
            return back();
        }

       if($request->code == $request->lesson){
            return $next($request);
       }

        // dd($request->all());
    }
}
