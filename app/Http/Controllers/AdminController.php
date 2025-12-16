<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
  use ApiResponse;

  /**
   * List all users with pagination.
   * 
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request): JsonResponse
  {
    $perPage = min($request->input('per_page', 10), 50);

    $users = User::withCount('recipes')
      ->latest()
      ->paginate($perPage);

    return $this->successResponse([
      'users' => UserResource::collection($users),
      'pagination' => [
        'current_page' => $users->currentPage(),
        'total_pages' => $users->lastPage(),
        'per_page' => $users->perPage(),
        'total_items' => $users->total(),
      ],
    ], 'Users retrieved successfully');
  }

  /**
   * Delete a user and all their recipes.
   * Admin cannot delete themselves.
   * 
   * @param Request $request
   * @param User $user
   * @return JsonResponse
   */
  public function destroy(Request $request, User $user): JsonResponse
  {
    // Prevent admin from deleting themselves
    if ($request->user()->id === $user->id) {
      return $this->errorResponse(
        'You cannot delete your own account',
        [],
        400
      );
    }

    // Delete user (recipes will be cascade deleted due to foreign key constraint)
    $user->delete();

    return $this->successResponse(
      null,
      'User and all associated recipes deleted successfully'
    );
  }
}
