<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Paginate user's created recipes
        $recipes = $user->recipes()->latest()->paginate(5);

        return view('user.profile', compact('user', 'recipes'));
    }
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'bio'  => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = 'storage/' . $path;
        }

        $user->name = $request->name;
        $user->bio  = $request->bio;
        $user->save();

        return back()->with('success', 'Profile updated!');
    }
}
