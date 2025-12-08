<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Recipe;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = \App\Models\User::all();
        $recipes = Recipe::all();

        foreach ($recipes as $recipe) {

            // Each recipe gets 3–6 comments
            $commentCount = rand(3, 6);

            for ($i = 0; $i < $commentCount; $i++) {

                $user = $users->random();

                Comment::create([
                    'user_id'    => $user->id,
                    'recipe_id'  => $recipe->id,
                    'parent_id'  => null,
                    'content'    => fake()->sentence(12),
                    'rating'     => rand(3, 5), // all comments have rating
                    'is_approved'=> true,
                ]);
            }
        }
    }
}
