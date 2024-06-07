<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $registerRequest)
    {
        try {

            //validate request
            $registerRequest->validated();


            //register user
            $user = User::create([
                'name' => $registerRequest->name,
                'username' => $registerRequest->username,
                'email' => $registerRequest->email,
                'password' => bcrypt($registerRequest->password),
            ]);

            $token = $user->createToken('threads')->plainTextToken;

            return response([
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(LoginRequest $loginRequest)
    {
        try {

            //validate data
            $loginRequest->validated();

            //check user
            $user = User::whereUsername($loginRequest->username)->first();
            if (!$user || !Hash::check($loginRequest->password, $user->password)) {
                return response([
                    'message' => 'Invalid Credentials',
                ], 422);
            }

            $token = $user->createToken('threads')->plainTextToken;

            return response([
                'user' => $user,
                'token' => $token
            ], 200);


        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // return response()->json([
    //     'message' => 'test',
    // ], 201);

}
