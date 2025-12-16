<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Http\Resources\RecipeListResource;
use App\Http\Resources\RecipeResource;
use App\Http\Traits\ApiResponse;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RecipeController extends Controller
{
  use ApiResponse;

  /**
   * List all recipes with pagination and optional search filter.
   * Public endpoint - no authentication required.
   *
   * @param Request $request
   * @return JsonResponse
   *
   * Requirements: 5.1, 5.2, 5.3
   */
  public function index(Request $request): JsonResponse
  {
    $perPage = min($request->input('per_page', 5), 50);
    $search = $request->input('search');

    $query = Recipe::with('user');

    // Apply search filter if provided
    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('title', 'like', "%{$search}%")
          ->orWhere('description', 'like', "%{$search}%");
      });
    }

    $recipes = $query->latest()->paginate($perPage);

    return $this->successResponse([
      'recipes' => RecipeListResource::collection($recipes),
      'pagination' => [
        'current_page' => $recipes->currentPage(),
        'total_pages' => $recipes->lastPage(),
        'per_page' => $recipes->perPage(),
        'total_items' => $recipes->total(),
      ],
    ], 'Recipes retrieved successfully');
  }


  /**
   * List authenticated user's own recipes with pagination.
   * Protected endpoint - authentication required.
   *
   * @param Request $request
   * @return JsonResponse
   *
   * Requirements: 5.4
   */
  public function myRecipes(Request $request): JsonResponse
  {
    $perPage = min($request->input('per_page', 5), 50);

    $recipes = $request->user()
      ->recipes()
      ->with('user')
      ->latest()
      ->paginate($perPage);

    return $this->successResponse([
      'recipes' => RecipeListResource::collection($recipes),
      'pagination' => [
        'current_page' => $recipes->currentPage(),
        'total_pages' => $recipes->lastPage(),
        'per_page' => $recipes->perPage(),
        'total_items' => $recipes->total(),
      ],
    ], 'Your recipes retrieved successfully');
  }

  /**
   * Get a specific recipe's detail.
   * Public endpoint - no authentication required.
   *
   * @param Recipe $recipe
   * @return JsonResponse
   *
   * Requirements: 6.1, 6.2
   */
  public function show(Recipe $recipe): JsonResponse
  {
    $recipe->load('user');

    return $this->successResponse(
      new RecipeResource($recipe),
      'Recipe retrieved successfully'
    );
  }

  /**
   * Create a new recipe.
   * Protected endpoint - authentication required.
   *
   * @param CreateRecipeRequest $request
   * @return JsonResponse
   *
   * Requirements: 4.1, 4.2, 4.3, 4.4
   */
  public function store(CreateRecipeRequest $request): JsonResponse
  {
    $recipe = $request->user()->recipes()->create([
      'title' => $request->title,
      'description' => $request->description,
      'ingredients' => $request->ingredients,
      'instructions' => $request->instructions,
    ]);

    $recipe->load('user');

    return $this->createdResponse(
      new RecipeResource($recipe),
      'Recipe created successfully'
    );
  }

  /**
   * Update an existing recipe.
   * Protected endpoint - authentication required, owner only.
   *
   * @param UpdateRecipeRequest $request
   * @param Recipe $recipe
   * @return JsonResponse
   *
   * Requirements: 7.1, 7.2, 7.3, 7.4
   */
  public function update(UpdateRecipeRequest $request, Recipe $recipe): JsonResponse
  {
    // Check if user is authorized to update this recipe
    if (Gate::denies('update', $recipe)) {
      return $this->forbiddenResponse('You are not authorized to update this recipe');
    }

    $recipe->update([
      'title' => $request->title,
      'description' => $request->description,
      'ingredients' => $request->ingredients,
      'instructions' => $request->instructions,
    ]);

    $recipe->load('user');

    return $this->successResponse(
      new RecipeResource($recipe),
      'Recipe updated successfully'
    );
  }

  /**
   * Delete a recipe.
   * Protected endpoint - authentication required, owner only.
   *
   * @param Recipe $recipe
   * @return JsonResponse
   *
   * Requirements: 8.1, 8.2, 8.3, 8.4
   */
  public function destroy(Recipe $recipe): JsonResponse
  {
    // Check if user is authorized to delete this recipe
    if (Gate::denies('delete', $recipe)) {
      return $this->forbiddenResponse('You are not authorized to delete this recipe');
    }

    $recipe->delete();

    return $this->successResponse(
      null,
      'Recipe deleted successfully'
    );
  }
}
