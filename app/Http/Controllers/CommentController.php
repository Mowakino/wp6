<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\RecipeRating;

class CommentController extends Controller
{
    /**
     * Store new comment or reply
     */
    public function store(Request $request, $recipeId)
    {
        $request->validate([
            'content' => 'required|string',
            'rating'  => 'nullable|integer|min:1|max:5',
        ]);

        $userId = auth()->id();

        // Save rating (one rating per user)
        if ($request->rating && !$request->parent_id) {
            RecipeRating::updateOrCreate(
                ['recipe_id' => $recipeId, 'user_id' => $userId],
                ['rating' => $request->rating]
            );
        }

        // Save rating inside comment too (so UI can show)
        $commentRating = $request->parent_id ? null : $request->rating;

        // Only one top-level comment allowed
        if (!$request->parent_id) {
            if (Comment::userHasCommented($recipeId, $userId)) {
                return back()->with('error', 'You have already commented on this recipe.');
            }
        }

        Comment::create([
            'user_id'   => $userId,
            'recipe_id' => $recipeId,
            'parent_id' => $request->parent_id,
            'content'   => $request->content,
            'rating'    => $commentRating,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Comment posted.');
    }


    /**
     * Update an existing comment (text + rating)
     */
    public function update(Request $request, Comment $comment)
    {
        // Only comment owner can edit
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string',
            'rating'  => 'nullable|integer|min:1|max:5',
        ]);

        // Update comment text
        $comment->update([
            'content' => $request->content,
        ]);

        // Handle rating updates ONLY for top-level comments
        if (!$comment->parent_id) {

            // If user selected rating → update or create rating
            if ($request->rating) {
                RecipeRating::updateOrCreate(
                    [
                        'recipe_id' => $comment->recipe_id,
                        'user_id'   => $comment->user_id
                    ],
                    ['rating' => $request->rating]
                );
            }
            // If user removed rating → delete rating row
            else {
                RecipeRating::where('recipe_id', $comment->recipe_id)
                            ->where('user_id', $comment->user_id)
                            ->delete();
            }
        }

        return back()->with('success', 'Comment updated.');
    }


    /**
     * Delete comment
     */
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $recipeId = $comment->recipe_id;

        // Delete comment
        $comment->delete();

        // Delete user's rating (if exists)
        RecipeRating::where('recipe_id', $recipeId)
                    ->where('user_id', $comment->user_id)
                    ->delete();

        return back()->with('success', 'Comment deleted.');
    }



    public function like(Comment $comment)
    {
        $user = auth()->user();
        $existing = $comment->voteByUser($user->id);

        if ($existing && $existing->vote === 1) {
            $existing->delete();
        } else {
            $comment->votes()->updateOrCreate(
                ['user_id' => $user->id],
                ['vote' => 1]
            );
        }

        return back();
    }


    public function dislike(Comment $comment)
    {
        $user = auth()->user();
        $existing = $comment->voteByUser($user->id);

        if ($existing && $existing->vote === -1) {
            $existing->delete();
        } else {
            $comment->votes()->updateOrCreate(
                ['user_id' => $user->id],
                ['vote' => -1]
            );
        }

        return back();
    }
}
