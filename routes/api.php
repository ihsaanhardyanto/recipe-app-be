<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
  Route::post('/register', [AuthController::class, 'register']);
  Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/recipes', [RecipeController::class, 'index']);
Route::get('/recipes/{recipe}', [RecipeController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
  Route::post('/auth/logout', [AuthController::class, 'logout']);

  Route::get('/my-recipes', [RecipeController::class, 'myRecipes']);
  Route::post('/recipes', [RecipeController::class, 'store']);
  Route::put('/recipes/{recipe}', [RecipeController::class, 'update']);
  Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy']);

  Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'index']);
    Route::delete('/users/{user}', [AdminController::class, 'destroy']);
  });
});
