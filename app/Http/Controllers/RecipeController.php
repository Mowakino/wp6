<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Comment;
use App\Models\RecipeRating;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        // Start query and include average rating from recipe_ratings
        $query = Recipe::query()
            ->withAvg('ratings', 'rating'); // adds ratings_avg_rating

        // Filters
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('cuisine')) {
            $query->where('cuisine', $request->cuisine);
        }

        if ($request->filled('time')) {
            $query->where('time_minutes', '<=', $request->time);
        }

        if ($request->filled('calories')) {
            $query->where('calories', '<=', $request->calories);
        }

        // Sort by rating (using ratings_avg_rating)
        if ($request->filled('sort_rating')) {
            $direction = $request->sort_rating === 'asc' ? 'asc' : 'desc';
            $query->orderBy('ratings_avg_rating', $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        $recipes = $query->paginate(12)->appends($request->query());

        return view('recipes.index', compact('recipes'));
    }


    public function show($id)
    {
        $recipe = Recipe::with(['user'])
            ->withAvg('ratings', 'rating')
            ->findOrFail($id);

        $sort = request('sort', 'newest');
        $rating = request('rating'); // 1–5 or null

        $comments = Comment::where('recipe_id', $id)
            ->whereNull('parent_id')

            // FIXED RATING FILTER
            ->when($rating, function ($q) use ($rating, $id) {
                $q->whereIn('id', function ($sub) use ($rating, $id) {
                    $sub->select('comments.id')
                        ->from('comments')
                        ->join('recipe_ratings', function ($join) {
                            $join->on('comments.recipe_id', '=', 'recipe_ratings.recipe_id')
                                ->on('comments.user_id', '=', 'recipe_ratings.user_id');
                        })
                        ->where('comments.recipe_id', $id)
                        ->whereNull('comments.parent_id')
                        ->where('recipe_ratings.rating', $rating);
                });
            })

            // SORTING
            ->when($sort === 'oldest', function ($q) {
                $q->orderBy('created_at', 'asc');
            })
            ->when($sort === 'newest', function ($q) {
                $q->orderBy('created_at', 'desc');
            })
            ->when($sort === 'liked', function ($q) {
                $q->withCount([
                    'votes as likes_count' => function ($v) {
                        $v->where('vote', 1);
                    }
                ])->orderBy('likes_count', 'desc');
            })
            ->when($sort === 'disliked', function ($q) {
                $q->withCount([
                    'votes as dislikes_count' => function ($v) {
                        $v->where('vote', -1);
                    }
                ])->orderBy('dislikes_count', 'desc');
            })
            ->when($sort === 'highest_rating', function($q) {
                $q->addSelect([
                    'user_rating' => RecipeRating::select('rating')
                        ->whereColumn('recipe_id', 'comments.recipe_id')
                        ->whereColumn('user_id', 'comments.user_id')
                        ->limit(1)
                ]);
                $q->orderByDesc('user_rating');
            })

            ->when($sort === 'lowest_rating', function($q) {
                $q->addSelect([
                    'user_rating' => RecipeRating::select('rating')
                        ->whereColumn('recipe_id', 'comments.recipe_id')
                        ->whereColumn('user_id', 'comments.user_id')
                        ->limit(1)
                ]);
                $q->orderBy('user_rating');
            })



            ->paginate(6)
            ->withQueryString();

        return view('recipes.show', compact('recipe', 'comments'));
    }

    /**
     * Toggle Favorite System (Add or Remove Favorite)
     */
    public function toggleFavorite(Recipe $recipe)
    {
        $user = auth()->user();

        if ($user->favorites()->where('recipe_id', $recipe->id)->exists()) {
            $user->favorites()->detach($recipe->id);
        } else {
            $user->favorites()->attach($recipe->id);
        }

        return back();
    }

    public function favorites()
    {
        $user = auth()->user();

        // Get only favorite recipes (you can also withAvg here if you want ratings on favorites page)
        $recipes = $user->favorites()
            ->withAvg('ratings', 'rating')
            ->paginate(12);

        return view('favorites', compact('recipes'));
    }

    // CREATE
    public function create()
    {
        return view('recipes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'difficulty' => 'required|string',
            'cuisine' => 'required|string',
            'time_minutes' => 'required|integer',
            'calories' => 'required|integer',
            'protein' => 'required|integer',
            'fat' => 'required|integer',
            'carbs' => 'required|integer',
            'image' => 'required|image|max:4096'
        ]);

        // store image publicly
        $path = $request->file('image')->store('recipes', 'public');
        $imagePath = 'storage/' . $path;

        Recipe::create([
            'user_id'     => auth()->id(),
            'name'        => $request->name,
            'description' => $request->description,
            'ingredients' => $request->ingredients,
            'instructions'=> $request->instructions,
            'difficulty'  => $request->difficulty,
            'cuisine'     => $request->cuisine,
            'time_minutes'=> $request->time_minutes,
            'calories'    => $request->calories,
            'protein'     => $request->protein,
            'fat'         => $request->fat,
            'carbs'       => $request->carbs,
            'image'       => $imagePath,
        ]);

        return redirect()->route('recipes.index')->with('success', 'Recipe created successfully!');
    }


    // EDIT / UPDATE
    public function edit(Recipe $recipe)
    {
        // Ensure the logged-in user owns this recipe
        if ($recipe->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('recipes.edit', compact('recipe'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        if ($recipe->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|string',
            'instructions'=> 'required|string',
            'time_minutes'=> 'required|integer',
            'calories'    => 'required|integer',
            'protein'     => 'required|integer',
            'fat'         => 'required|integer',
            'carbs'       => 'required|integer',
            'difficulty'  => 'required|string',
            'cuisine'     => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        // Update all text fields
        $recipe->update([
            'name'        => $request->name,
            'description' => $request->description,
            'ingredients' => $request->ingredients,
            'instructions'=> $request->instructions,
            'time_minutes'=> $request->time_minutes,
            'calories'    => $request->calories,
            'protein'     => $request->protein,
            'fat'         => $request->fat,
            'carbs'       => $request->carbs,
            'difficulty'  => $request->difficulty,
            'cuisine'     => $request->cuisine,
        ]);

        // If user uploaded new image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('recipes', 'public');
            $recipe->image = 'storage/' . $path;
            $recipe->save();
        }

        return redirect()->route('profile')
            ->with('success', 'Recipe updated successfully!');
    }

    // DELETE
    public function destroy(Recipe $recipe)
    {
        if ($recipe->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $recipe->delete();

        return redirect()->route('profile')
            ->with('success', 'Recipe deleted successfully!');
    }

}
