<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;
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

// Public recipe routes (no authentication required)
Route::get('/recipes', [RecipeController::class, 'index']);
Route::get('/recipes/{recipe}', [RecipeController::class, 'show']);

// Protected routes (requires authentication)
Route::middleware('auth:sanctum')->group(function () {
  // Auth routes
  Route::post('/auth/logout', [AuthController::class, 'logout']);

  // Protected recipe routes
  Route::get('/my-recipes', [RecipeController::class, 'myRecipes']);
  Route::post('/recipes', [RecipeController::class, 'store']);
  Route::put('/recipes/{recipe}', [RecipeController::class, 'update']);
  Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy']);

  // Admin routes (requires admin role)
  Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'index']);
    Route::delete('/users/{user}', [AdminController::class, 'destroy']);
  });
});
