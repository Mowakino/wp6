<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Recipe;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $recipes = Recipe::all();

        foreach ($users as $user) {

            // user favorites 3–7 recipes
            $favoriteCount = rand(3, 7);
            $favorites = $recipes->random($favoriteCount);

            foreach ($favorites as $recipe) {
                $user->favorites()->syncWithoutDetaching($recipe->id);
            }
        }
    }
}
