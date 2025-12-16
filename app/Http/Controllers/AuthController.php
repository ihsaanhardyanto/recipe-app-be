<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Handles user authentication operations.
 * 
 * Validates: Requirements 1.1, 2.1, 2.2, 2.3, 3.1, 3.2
 */
class AuthController extends Controller
{
  /**
   * Register a new user.
   * 
   * Creates a new user account and returns a Sanctum token.
   * Validates: Requirements 1.1, 1.2, 1.3, 1.4
   */
  public function register(RegisterRequest $request): JsonResponse
  {
    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return $this->createdResponse([
      'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
      ],
      'token' => $token,
    ], 'User registered successfully');
  }

  /**
   * Login user.
   * 
   * Validates credentials and returns a Sanctum token.
   * Validates: Requirements 2.1, 2.2, 2.3
   */
  public function login(LoginRequest $request): JsonResponse
  {
    if (!Auth::attempt($request->only('email', 'password'))) {
      return $this->unauthorizedResponse('Invalid credentials');
    }

    $user = Auth::user();
    $token = $user->createToken('auth_token')->plainTextToken;

    return $this->successResponse([
      'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
      ],
      'token' => $token,
    ], 'Login successful');
  }

  /**
   * Logout user.
   * 
   * Revokes the current Sanctum token.
   * Validates: Requirements 3.1, 3.2
   */
  public function logout(Request $request): JsonResponse
  {
    $request->user()->currentAccessToken()->delete();

    return $this->successResponse(null, 'Logged out successfully');
  }
}
