<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'ingredients',
        'instructions',
        'difficulty',
        'cuisine',
        'time_minutes',
        'calories',
        'protein',
        'fat',
        'carbs',
        'image'
    ];

    /**
     * User who created the recipe
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Users who favorited this recipe
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /**
     * Top-level comments (approved only)
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)
                    ->whereNull('parent_id')
                    ->where('is_approved', true)
                    ->orderBy('created_at', 'desc');
    }

    /**
     * All comments including replies (no filter)
     */
    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Recipe ratings
     */
    public function ratings()
    {
        return $this->hasMany(RecipeRating::class);
    }

    /**
     * Average rating (computed)
     */
    public function getAverageRatingAttribute()
    {
        return round($this->ratings()->avg('rating') ?? 0, 1);
    }

    /**
     * Get the current logged-in user's rating for this recipe
     */
    public function getUserRatingAttribute()
    {
        if (!auth()->check()) {
            return null;
        }

        return $this->ratings()
                    ->where('user_id', auth()->id())
                    ->value('rating');
    }
}
