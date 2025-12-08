<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipe_id',
        'parent_id',
        'content',
        'rating',
        'is_approved',
        'likes',
        'dislikes',
    ];

    /** Comment belongs to a User */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Comment belongs to a Recipe */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    /** Replies */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /** Parent comment */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /** Check if user already posted a root comment */
    public static function userHasCommented($recipeId, $userId)
    {
        return self::where('recipe_id', $recipeId)
            ->where('user_id', $userId)
            ->whereNull('parent_id')
            ->exists();
    }

    /** Votes relation */
    public function votes()
    {
        return $this->hasMany(CommentVote::class);
    }

    public function getLikesAttribute()
    {
        return $this->votes()->where('vote', 1)->count();
    }

    public function getDislikesAttribute()
    {
        return $this->votes()->where('vote', -1)->count();
    }

    public function voteByUser($userId)
    {
        return $this->votes()->where('user_id', $userId)->first();
    }

    /**
     * ⬅️ FIXED: Retrieve rating using clean query (no alias issues)
     */
    public function ratingRecord()
    {
        return RecipeRating::where('recipe_id', $this->recipe_id)
                           ->where('user_id', $this->user_id);
    }

    public function getGivenRatingAttribute()
    {
        return $this->ratingRecord()->value('rating');
    }
}
