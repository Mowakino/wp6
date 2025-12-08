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
        'rating',        // Rating stored only for top-level comments
        'is_approved',
    ];

    /**
     * User who posted the comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Recipe this comment belongs to
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Replies (children)
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * A comment is a reply if parent_id is not null
     */
    public function isReply()
    {
        return $this->parent_id !== null;
    }

    /**
     * Parent comment
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Check if user already created a top-level comment for this recipe
     */
    public static function userHasCommented($recipeId, $userId)
    {
        return self::where('recipe_id', $recipeId)
            ->where('user_id', $userId)
            ->whereNull('parent_id') // Only root comments
            ->exists();
    }

    /**
     * Votes on this comment
     */
    public function votes()
    {
        return $this->hasMany(CommentVote::class);
    }

    /**
     * Total likes
     */
    public function getLikesAttribute()
    {
        return $this->votes()->where('vote', 1)->count();
    }

    /**
     * Total dislikes
     */
    public function getDislikesAttribute()
    {
        return $this->votes()->where('vote', -1)->count();
    }

    /**
     * Vote record by specific user
     */
    public function voteByUser($userId)
    {
        return $this->votes()->where('user_id', $userId)->first();
    }
    public function getGivenRatingAttribute()
    {
        return RecipeRating::where('recipe_id', $this->recipe_id)
                        ->where('user_id', $this->user_id)
                        ->value('rating');
    }
}
