<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
        'role' => $user->role,
      ],
      'token' => $token,
    ], 'User registered successfully');
  }

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
        'role' => $user->role,
      ],
      'token' => $token,
    ], 'Login successful');
  }

  public function logout(Request $request): JsonResponse
  {
    $request->user()->currentAccessToken()->delete();

    return $this->successResponse(null, 'Logged out successfully');
  }
}
