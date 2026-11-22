<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        // validate the data with rules from RegisterRequest class
        $data = $request->validated();

        // create a new user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']) // encrypt the password before sending it to database
        ]);

        // save device name to mitigating/prevent multiple device login problem
        $deviceName = $data['device_name'] ?? $request->userAgent() ?? 'api-client';
        // create newly fresh token
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'message' => 'Register successful',
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ], 201);
    }

    public function login(LoginRequest $request) {
        $credentials = $request->validated();

        // fetch user data based on requested data, to check if there isn't an existing user
        $user = User::where('email', $credentials['email'])->first();

        // if user doesn't exist or the password from request doesn't match with the exact user password, send error message and code 401
        if(!$user || Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Email or password is invalid'
            ], 401);
        }

        // store device name as user agent to make uniquely token to prevent multiple session
        $deviceName = $credentials['device_name'] ?? $request->userAgent() ?? 'api-client';

        // prevent token to be used in same device
        $user->tokens()->where('name',$deviceName)->delete();

        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ], 200);


    }

    public function logout() {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
