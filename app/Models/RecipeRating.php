<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeRating extends Model
{
    protected $fillable = [
        'recipe_id',
        'user_id',
        'rating',
    ];

    /**
     * Rating belongs to a recipe
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Rating belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get rating value for given user + recipe
     */
    public static function userRating($recipeId, $userId)
    {
        return self::where('recipe_id', $recipeId)
                   ->where('user_id', $userId)
                   ->value('rating');
    }
}
