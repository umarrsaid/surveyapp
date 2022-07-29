<?php

namespace App\Http\Middleware;

use Log;
use Closure;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class TokenDevice
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
            $response = $request->header('Authorization');
            Log::info(' =========================== AuthServiceProvide ======================================== ');
            Log::info($request->header('Authorization'). ' Header');

            if ($response) {
                $bearer = explode(' ',$response);
                if ((is_array($bearer)) && (isset($bearer[1])) && ($bearer[0] == 'Bearer')) {
                    $token = $bearer[1];
                }else{
                    $token = '';
                }
            }else{
                $token = '';
            }
            if ($token != '') {
                $token_api = \DB::select(\DB::raw('select * from device where api_token = "'.$token.'" AND deleted_at IS NULL'));

                Log::info($token. ' try accessed');
                Log::info((isset($token_api[0]->token) ? $token_api[0]->token : 'Failed'). ' accessed');

                if (empty($token_api)) {
                    return response()->json([
                        'message' => 'Unaunthorized',
                    ],401); 
                }

            }else{
                return response()->json([
                    'message' => 'Unaunthorized'
                ]);
            }
                return $next($request);

        // });

    }
}
