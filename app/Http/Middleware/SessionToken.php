<?php

namespace App\Http\Middleware;

use Closure;

class SessionToken
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
        // dd('tes');
        $response = $next($request);

        // if (session('user')[0]->accessToken) {
        if ( session('user') != null ) {
            $token = session('user')[0]->accessToken;
            $response->header('Authorization', 'Bearer '.$token);
        }
        // dd($response);
        return $response;
        // return $next($request);
    }
}
