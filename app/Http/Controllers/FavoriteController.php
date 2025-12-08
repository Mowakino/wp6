<?php

namespace App\Http\Controllers;

use App\Models\Recipe;

class FavoriteController extends Controller
{
    public function index()
    {
        // Get all favorited recipes of the logged-in user
        $recipes = auth()->user()->favorites()->paginate(9);
        return view('favorites', compact('recipes'));
    }
}
