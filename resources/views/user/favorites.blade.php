<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Favorites - Leaf & Spoon</title>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('bootstrap-5.0.2-dist/css/bootstrap.min.css') }}">

    <style>
        body {
            background: #fafafa;
            font-family: "Nunito Sans", sans-serif;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 32px;
        }

        .card {
            background: #fff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            transition: .22s;
            text-decoration: none;
            color: #222;
        }
        .card:hover { transform: translateY(-8px); }

        .img-wrap {
            height: 220px;
            position: relative;
        }

        .img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rating-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            background: rgba(255,255,255,0.85);
            padding: 6px 12px;
            border-radius: 15px;
            backdrop-filter: blur(4px);
            font-size: 13px;
            font-weight: bold;
        }

        .content { padding: 18px 22px 22px; }

        .title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .badges { display: flex; gap: 10px; margin-bottom: 14px; }

        .badge.cuisine { background: #e6f6ec; color: #2d6a4f; }

        .diff-easy { background:#d8f8d8;color:#2b7a2b; }
        .diff-medium { background:#fff2c6;color:#8a6a00; }
        .diff-hard { background:#ffd7d7;color:#b33636; }

        .nutrition { display: flex; gap: 6px; flex-wrap: wrap; font-size: 14px; color: #555; }
        .nutrition .dot { margin: 0 4px; }

        .skeleton-card {
            background: #eaeaea;
            height: 320px;
            border-radius: 20px;
            animation: pulse 1.3s infinite;
        }
        @keyframes pulse {
            0% { opacity: .7; }
            50% { opacity: .35; }
            100% { opacity: .7; }
        }
    </style>
</head>

<body>

<x-navbar />

<div class="container py-5">

    {{-- PAGE TITLE --}}
    <h1 class="fw-bold mb-4">Your Favorite Recipes</h1>

    @if ($recipes->isEmpty())
        <div class="text-center py-5">

            <img src="{{ asset('svg/empty.svg') }}" 
                alt="No favorites" 
                style="width:140px; opacity:0.6;" class="mb-3">

            <h3 class="fw-bold">No Favorite Recipes Yet</h3>
            <p class="text-muted mb-4">Save recipes you love and they will appear here.</p>

            <a href="{{ route('recipes.index') }}" class="btn btn-success px-4">
                Browse Recipes →
            </a>
        </div>
    @else

        {{-- SKELETON --}}
        <div id="skeleton-wrap" class="grid">
            @for($i=0; $i<6; $i++)
                <div class="skeleton-card"></div>
            @endfor
        </div>

        {{-- REAL CONTENT --}}
        <div id="real-content" style="display:none;">
            <div class="grid">
                @foreach ($recipes as $recipe)
                <a class="card" href="{{ route('recipes.show', $recipe->id) }}">
                    <div class="img-wrap">
                        <img src="{{ asset($recipe->image) }}" alt="{{ $recipe->name }}">
                        <div class="rating-badge">⭐ {{ number_format($recipe->average_rating, 1) }}</div>
                    </div>

                    <div class="content">
                        <div class="title">{{ $recipe->name }}</div>

                        <div class="d-flex align-items-center text-muted mb-2" style="font-size: 14px;">
                            <img src="{{ asset('svg/clock.svg') }}"
                                alt="time"
                                style="width: 14px; height: 14px; margin-right: 6px; opacity: 0.7;">
                            {{ $recipe->time_minutes }} min
                        </div>

                        <div class="badges">
                            <span class="badge cuisine">{{ $recipe->cuisine }}</span>

                            @if($recipe->difficulty=='Easy')
                                <span class="badge diff diff-easy">Easy</span>
                            @elseif($recipe->difficulty=='Medium')
                                <span class="badge diff diff-medium">Medium</span>
                            @else
                                <span class="badge diff diff-hard">Hard</span>
                            @endif
                        </div>

                        <div class="nutrition">
                            <span>{{ $recipe->calories }} kcal</span>
                            <span class="dot">•</span>
                            <span>{{ $recipe->protein }}g Protein</span>
                            <span class="dot">•</span>
                            <span>{{ $recipe->fat }}g Fat</span>
                            <span class="dot">•</span>
                            <span>{{ $recipe->carbs }}g Carbs</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $recipes->links('pagination::bootstrap-5') }}
            </div>

        </div>
    @endif

</div>

<x-footer />

<script>
document.addEventListener("DOMContentLoaded", () => {
    setTimeout(() => {
        const sk = document.getElementById("skeleton-wrap");
        const real = document.getElementById("real-content");

        if (sk) sk.style.display = "none";
        if (real) real.style.display = "block";
    }, 600);
});
</script>

<script src="{{ asset('bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
