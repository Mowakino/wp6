<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Recipe;
use App\Models\Comment;
use App\Models\RecipeRating;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $recipes = Recipe::all();

        foreach ($recipes as $recipe) {

            // pick 3–5 random users to comment on each recipe
            $commenters = $users->random(rand(3, 5));

            foreach ($commenters as $user) {

                // Prevent duplicate comments
                if (Comment::where('recipe_id', $recipe->id)
                           ->where('user_id', $user->id)
                           ->exists()) {
                    continue; // skip duplicates
                }

                // Random rating 1–5
                $rating = rand(1, 5);

                // Save rating
                RecipeRating::updateOrCreate(
                    ['recipe_id' => $recipe->id, 'user_id' => $user->id],
                    ['rating' => $rating]
                );

                // Create comment
                Comment::create([
                    'recipe_id' => $recipe->id,
                    'user_id' => $user->id,
                    'content' => fake()->sentence(8),
                    'rating' => $rating,
                    'is_approved' => true,
                ]);
            }
        }
    }
}
