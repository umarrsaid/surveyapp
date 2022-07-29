<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Log;
        use Lcobucci\JWT\Parser;

class AuthController extends Controller
{
	public function loginPage(){
        if (session('user') != null) {
            if (session('user')[0]->accessToken) {
                return redirect()->back();
            }
        }else{
            return view('auth.login');
        }

    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
    	$validator = \Validator::make($request->all(), [
			'nama' => 'required',
			'password' => 'required',
		]);

		if ($validator->fails()) {
			Log::info(response()->json(["message" => $validator->errors()->all()], 400));
			return response()->json(["message" => $validator->errors()->all()], 400);
		}

        // $request->validate([
        //     'email' => 'required|string|email',
        //     'password' => 'required|string',
        //     'remember_me' => 'boolean'
        // ]);
        $input = $request->all();
        Log::notice(' ================================= Login Survey =================================== ');
        Log::info(json_encode($input));

        if(Auth::attempt(['nama' => request('nama'), 'password' => request('password')])){

	        $user = $request->user();
	        $tokenResult = $user->createToken('Personal Access Token');
	        $token = $tokenResult->token;

            $token->expires_at = Carbon::now()->addWeeks(1);
        	$token->save();

		    if ($request->type == 'web') {
		        $request->session()->forget('user');
		        $request->session()->push('user', $tokenResult);
		    }

            Log::notice(' Login Sukses ');
            Log::notice(' =============================== End Login Survey =================================== ');
	        return response()->json([
	            'access_token' => $tokenResult->accessToken,
	            'token_type' => 'Bearer',
	            'expires_at' => Carbon::parse(
	                $tokenResult->token->expires_at
	            )->toDateTimeString()
	        ]);

        }else{
            Log::notice(' Login Failed ');
            Log::notice(' =============================== End Login Survey =================================== ');
    	    return response()->json([
                'message' => 'Unauthorized'
            ], 401);
       
        }
		// }else{
			// return 'j';
		// }
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {	
    	// $data = \Session::all();

    	$data = session('user')[0]->accessToken;
    	dd($data);
        return response()->json($request->user());
    }

    public function destroy(Request $request){
        if (session('user') != null) {
    
            $value = session('user')[0]->accessToken;
            $id = (new Parser())->parse($value)->getHeader('jti');
    
            \DB::table('oauth_access_tokens')
            ->where('id', $id)
            ->update([
                'revoked' => true
            ]);
        }
        $tkn = $request->session()->forget('user');
        return back();

    }

}
