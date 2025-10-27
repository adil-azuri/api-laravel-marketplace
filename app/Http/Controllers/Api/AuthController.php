<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
         // 1. Validate Input
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'fullname' => 'required|string|max:255  ',
            'password' => 'required|string|min:6',
        ]);

         // 2. Create User
        $user = User::create([
            'username' => $request->username,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),             
        ]);

        // 3. Response Json Success
        return response()->json([
            'status' => 'success',
            'message' => 'Register Success',
            'user' => $user
        ], 201);

    } 

     public function login(Request $request)
    {
        // 1. Validate Input 
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Get User Input from username
         $user_input = $request->username; 

        // Search user by email or username
        $user = User::where('email', $user_input)
                ->orWhere('username', $user_input) 
                ->first();

        // 3. Check user exist and check password match
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email Or Password Invalid'],
            ]);
        }

        // 4. Delete old token
        $user->tokens()->delete();

        // 5. Generate new token
        $token = $user->createToken('authToken')->plainTextToken;

        // Response format
        return response()->json([
            'status' => 'success',
            'message' => 'Login Success.',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout Success',
            'status' => 'success'
        ],200);
    }
}
    