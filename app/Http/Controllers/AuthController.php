<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
    
        $request['password']=Hash::make($request['password']);
        $user = User::create($request->toArray());
    
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token];
    
        return response($response, 200);
    
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
    public function login (Request $request) {
        $user = User::where('email', $request->email)->first();
    
        if ($user) {
    
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['token' => $token];
                return response($response, 200);
            } else {
                $response = "Password missmatch";
                return response($response, 422);
            }
    
        } else {
            $response = 'User does not exist';
            return response($response, 422);
        }
    
    }
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
    
        $response = 'You have been succesfully logged out!';
        return response($response, 200);
    
    }
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}