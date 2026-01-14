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
        // $fields = $request->validate([
        //     'name' => 'required|string',
        //     'email' => 'required|string|unique:users,email',
        //     'password' => 'required'
        // ]);
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|min:6'
        ], [
            // Custom messages
            'name.required' => 'Please provide your name',
            'email.required' => 'Email address is required',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email is already registered. Try logging in instead.',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters long'
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
    public function Login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 401,
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
    // public function Logout() {
    //     Auth::logout();
    //     return response()->json([
    //         'status' => 200,
    //         'success' => true,
    //         'message' => 'User logged out successfully'
    //     ]);
    // }


    // logout controller
    public function Logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'User logged out successfully'
        ]);
    }

    // profile controller
    public function Profile()
    {
        $user = Auth::user();
        return response()->json([
            'status' => 200,
            'success' => true,
            'user' => $user
        ]);
    }
};
