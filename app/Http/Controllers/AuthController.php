<?php

namespace App\Http\Controllers;

use App\User;
use JWTAuth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->
    // }

    public function register(Request $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    public function login() {
        $credentials = request(['email', 'password']);
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                $code = 404;
                $response = ['code'=>$code, 'message'=>'email yang anda masukan salah'];
                return response()->json($response, $code);
            }
        } catch (JWTException $e) {
            $response = ['status'=>$e];
            return response()->json($response, 404);
        }

        return $this->respondWithToken($token);
    }

    public function me() {
        return response()->json(auth()->user());
    }

    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

}
