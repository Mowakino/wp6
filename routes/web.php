<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Recipe;

Route::get('/', function () {
    return redirect('/login');
});

// Home
Route::get('/home', function () {
    $recipes = Recipe::withAvg('ratings', 'rating')
                     ->orderByDesc('ratings_avg_rating')
                     ->orderBy('id')
                     ->take(5)
                     ->get();

    return view('home', compact('recipes'));
})->name('home');

// About
Route::get('/about', function () {
    return view('about');
})->name('about-us');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Recipes Listing
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');

// ✅ CREATE MUST COME BEFORE SHOW
Route::get('/recipes/create', [RecipeController::class, 'create'])
    ->middleware('auth')
    ->name('recipes.create');

Route::post('/recipes', [RecipeController::class, 'store'])
    ->middleware('auth')
    ->name('recipes.store');

// SHOW SINGLE RECIPE
Route::get('/recipes/{id}', [RecipeController::class, 'show'])->name('recipes.show');

// Comments
// STORE COMMENT / REPLY
Route::post('/recipes/{id}/comments', [CommentController::class, 'store'])
    ->name('comments.store')
    ->middleware('auth');

// LIKE / DISLIKE
Route::post('/comments/{comment}/like', [CommentController::class, 'like'])
    ->name('comments.like')
    ->middleware('auth');

Route::post('/comments/{comment}/dislike', [CommentController::class, 'dislike'])
    ->name('comments.dislike')
    ->middleware('auth');

// EDIT COMMENT
Route::patch('/comments/{comment}', [CommentController::class, 'update'])
    ->name('comments.update')
    ->middleware('auth');

// DELETE COMMENT
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
    ->name('comments.destroy')
    ->middleware('auth');


// Favorites
Route::post('/recipes/{recipe}/favorite', [RecipeController::class, 'toggleFavorite'])
    ->middleware('auth')
    ->name('recipes.favorite');

Route::get('/favorites', [FavoriteController::class, 'index'])
    ->middleware('auth')
    ->name('favorites.index');

// Profile
Route::get('/profile', [ProfileController::class, 'index'])
    ->middleware('auth')
    ->name('profile');
//edit profile
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
// Edit form
Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])
    ->middleware('auth')
    ->name('recipes.edit');

// Update recipe
Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])
    ->middleware('auth')
    ->name('recipes.update');

// Delete recipe
Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])
    ->middleware('auth')
    ->name('recipes.destroy');
