<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Recipe - Leaf & Spoon</title>

    <link rel="stylesheet" href="{{ asset('bootstrap-5.0.2-dist/css/bootstrap.min.css') }}">

    <style>
        body {
            background: #fafafa;
            font-family: "Nunito Sans", sans-serif;
        }

        .form-wrapper {
            width: 90%;
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.08);
            margin-top: 40px;
            margin-bottom: 60px;
        }

        .title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<x-navbar />

<div class="form-wrapper">

    <h1 class="title">Create Your Recipe</h1>

    <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Recipe Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Short Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Cuisine</label>
                <select name="cuisine" class="form-select" required>
                    <option value="">Choose...</option>
                    <option>American</option>
                    <option>Asian</option>
                    <option>Chinese</option>
                    <option>Indian</option>
                    <option>Indonesian</option>
                    <option>Italian</option>
                    <option>Japanese</option>
                    <option>Mexican</option>
                    <option>Western</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Difficulty</label>
                <select name="difficulty" class="form-select" required>
                    <option value="">Choose...</option>
                    <option>Easy</option>
                    <option>Medium</option>
                    <option>Hard</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Cooking Time (minutes)</label>
                <input type="number" name="time_minutes" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Calories</label>
                <input type="number" name="calories" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Protein (g)</label>
                <input type="number" name="protein" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Fat (g)</label>
                <input type="number" name="fat" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Carbs (g)</label>
                <input type="number" name="carbs" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Ingredients</label>
            <textarea name="ingredients" rows="6" class="form-control" placeholder="Use line breaks for each ingredient" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Instructions</label>
            <textarea name="instructions" rows="6" class="form-control" placeholder="Step-by-step instructions..." required></textarea>
        </div>

        <div class="mb-4">
            <label class="form-label">Recipe Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <button class="btn btn-success px-4">Publish Recipe</button>
    </form>

</div>

<x-footer />

<script src="{{ asset('bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
