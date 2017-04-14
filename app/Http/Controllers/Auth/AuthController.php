<?php

namespace App\Http\Controllers\Auth;


use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\{RegisterFormRequest, LoginFormRequest};

class AuthController extends Controller
{
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    public function login(LoginFormRequest $request)
    {
        try {
            if (!$token = $this->auth->attempt($request->only('email', 'password'))) {
                return response()->json([
                    'errors' => [
                        'root' => 'Could not sign you in with those details'
                    ]

                ], 401);

            }
        } catch (JWTException $e) {
            return response()->json([
                'errors' => [
                    'root' => 'Failed.'
                ]

            ], $e->getStatusCode());
        }

        // respond with jwt
        return response()->json([
            'data' => $request->user(),
            'meta' => [
                'token' => $token
            ]
        ], 200);
    }

    public function register(RegisterFormRequest $request)
    {
        // create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // attempt authentication
        $token = $this->auth->attempt($request->only('email', 'password'));

        // respond with jwt
        return response()->json([
            'data' => $user,
            'meta' => [
                'token' => $token
            ]
        ], 200);


        // respond with user data
    }

    public function logout()
    {
        $this->auth->invalidate($this->auth->getToken());

        return response(null, 200);
    }

    public function user(Request $request)
    {
        return response()->json([
           'data' => $request->user(),
        ]);
    }


}
