<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Generate Unique User ID
    protected function generateUserId()
    {
        do {
            // Example: 23101925
            $user_id = date('d') . rand(00, 99) . date('m') . rand(00, 99) . date('y');
        } while (DB::table('users')->where('user_id', $user_id)->exists());

        return $user_id;
    }

    // Register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'user_id'  => $this->generateUserId(),
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user_id' => $user->user_id,
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'message' => 'Logged in successfully',
            'token'   => $token,
        ], 200);
    }

    // Dashboard (Protected)
    public function dashboard()
    {
        $user = Auth::user();

        return response()->json([
            'message' => 'Dashboard data fetched successfully',
            'user'    => [
                'user_id' => $user->user_id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ], 200);
    }

    // Logout (Invalidate token)
    public function logout()
    {
        try {
            Auth::logout(); // invalidates the current JWT token

            return response()->json([
                'message' => 'Logged out successfully'
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Failed to logout, token invalid or expired'
            ], 500);
        }
    }

    // Refresh Token
    // public function refresh()
    // {
    //     try {
    //         $newToken = JWTAuth::refresh(JWTAuth::getToken());

    //         return response()->json([
    //             'message' => 'Token refreshed successfully',
    //             'token'   => $newToken,
    //         ], 200);
    //     } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
    //         return response()->json(['error' => 'Token refresh failed'], 500);
    //     }
    // }
}
