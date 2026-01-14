<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // regiister controller
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
            "message" => 'User registered successfully',
        ];

        return response($response, 201);
    }

    // login controller
    // public function Login(Request $request){
    //     $fields = $request->validate([
    //         'email' => 'required|string',
    //         'password' => 'required|string'
    //     ]);

    //     // check email
    //     $user = User::where('email', $fields['email'])->first();

    //     // check password
    //     if(!$user || !bcrypt($fields['password'], $user->password)){
    //         return response([
    //             'message' => 'Bad creds'
    //         ], 401);
    //     }

    //     $token = $user->createToken('myapptoken')->plainTextToken;

    //     $response = [
    //         'user' => $user,
    //         'token' => $token
    //     ];
    //     return response($response, 201);
    // }


    // login controller 
    public function Login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Invalid credentials'
            ]);
        };

        $user = Auth::user();
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'status' => 200,
            'success' => true,
            'user' => $user,
            'token' => $token,
            'message' => 'User logged in successfully'
        ]);
    }


    // logout controller
    public function Logout() {
        Auth::logout();
        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'User logged out successfully'
        ]);
    }

    // profile controller
    public function Profile() {
        $user = Auth::user();
        return response()->json([
            'status' => 200,
            'success' => true,
            'user' => $user
        ]);
    }
};
