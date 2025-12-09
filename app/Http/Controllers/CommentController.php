<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\RecipeRating;

class CommentController extends Controller
{
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

        $commentRating = $request->parent_id ? null : $request->rating;

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

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string',
            'rating'  => 'nullable|integer|min:1|max:5',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        if (!$comment->parent_id) {

            if ($request->rating) {
                RecipeRating::updateOrCreate(
                    [
                        'recipe_id' => $comment->recipe_id,
                        'user_id'   => $comment->user_id
                    ],
                    ['rating' => $request->rating]
                );
            }
            else {
                RecipeRating::where('recipe_id', $comment->recipe_id)
                            ->where('user_id', $comment->user_id)
                            ->delete();
            }
        }

        return back()->with('success', 'Comment updated.');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $recipeId = $comment->recipe_id;

        $comment->delete();

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
