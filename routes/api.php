<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Public auth routes
Route::prefix('auth')->group(function () {
  Route::post('/register', [AuthController::class, 'register']);
  Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes (requires authentication)
Route::middleware('auth:sanctum')->group(function () {
  // Auth routes
  Route::post('/auth/logout', [AuthController::class, 'logout']);

  // Recipe routes will be added here later
});
