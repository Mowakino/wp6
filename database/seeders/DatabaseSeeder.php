<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RecipeSeeder::class,
            CommentSeeder::class,
            CommentVoteSeeder::class,
            FavoriteSeeder::class,
        ]);

        \App\Models\Recipe::all()->each(function ($recipe) {
            $recipe->rating = $recipe->ratings()->avg('rating') ?? 0;
            $recipe->save();
        });
    }
}
