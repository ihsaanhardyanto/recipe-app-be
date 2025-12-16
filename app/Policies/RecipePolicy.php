<?php

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;

class RecipePolicy
{
  /**
   * Determine whether the user can update the recipe.
   * Only the recipe owner can update it.
   */
  public function update(User $user, Recipe $recipe): bool
  {
    return $user->id === $recipe->user_id;
  }

  /**
   * Determine whether the user can delete the recipe.
   * Recipe owner OR admin can delete it.
   */
  public function delete(User $user, Recipe $recipe): bool
  {
    if ($user->isAdmin()) {
      return true;
    }

    return $user->id === $recipe->user_id;
  }
}
