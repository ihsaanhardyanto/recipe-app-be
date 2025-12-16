<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'user_id',
    'title',
    'description',
    'ingredients',
    'instructions',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'ingredients' => 'array',
    ];
  }

  /**
   * Get the user that owns the recipe.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
