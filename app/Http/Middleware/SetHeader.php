<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class SetHeader
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
        // $request->headers->set('Accept','application/json');
        if(isset(session()->get('user')[0]->accessToken))
        {
            $request->headers->set('Authorization','Bearer '.session()->get('user')[0]->accessToken);
        }else{
            Auth::logout();
        }

        return  $next($request);
    }
}
