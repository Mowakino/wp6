<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $recipe->name }} - Leaf & Spoon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('bootstrap-5.0.2-dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/recipe-show.css') }}">
    <style>
        .ingredients-box, .steps-box {
            background: #ffffff;
            border: 1px solid #e3e3e3;
            border-radius: 12px;
            padding: 22px 26px;
            margin-top: 10px;
        }

        .ingredients-box ul,
        .steps-box ol {
            padding-left: 18px;
            margin: 0;
            line-height: 1.55;
            font-size: 15px;
        }

        .section-title {
            font-size: 26px;
            font-weight: 600;
            margin-top: 45px;
            margin-bottom: 12px;
        }

        .steps-box ol li + li {
            margin-top: 10px;
        }

    </style>
</head>

<body>

<x-navbar />

<div class="wrapper">

    <!-- SKELETON -->
    <div id="skeleton">
        <div class="row gx-5 mt-5">
            <div class="col-md-7">
                <div class="skeleton skel-title"></div>
                <div class="skeleton skel-line"></div>
                <div class="skeleton skel-line"></div>
            </div>
            <div class="col-md-5">
                <div class="skeleton skel-img"></div>
            </div>
        </div>
    </div>

    <!-- ACTUAL CONTENT -->
    <div id="content" style="display:none;">

        <div class="row gx-5 mt-5">

            <!-- LEFT -->
            <div class="col-md-7">
                <h1 class="recipe-title">{{ $recipe->name }}</h1>

                <div class="meta-row d-flex flex-wrap gap-2 mt-2">
                    <span class="d-flex align-items-center gap-1">
                        <img src="{{ asset('svg/clock.svg') }}" style="width:16px;opacity:.8;">
                        {{ $recipe->time_minutes }} min
                    </span>

                    <span>•</span>

                    <span class="badge-diff {{ strtolower($recipe->difficulty) }}">
                        {{ $recipe->difficulty }}
                    </span>

                    <span>•</span>
                    <span class="badge-diff cuisine">{{ $recipe->cuisine }}</span>

                    <span>•</span>
                    <span>Rating: {{ number_format($recipe->average_rating,1) }} ★</span>
                </div>

                <div class="mt-3 text-muted">by <strong>{{ $recipe->user->name }}</strong></div>

                <p class="desc-text mt-3">{{ $recipe->description }}</p>

                <div class="nutrition-box">
                    <strong>Nutrition Per Serving</strong><br>
                    Calories: {{ $recipe->calories }} kcal<br>
                    Protein: {{ $recipe->protein }} g<br>
                    Fat: {{ $recipe->fat }} g<br>
                    Carbs: {{ $recipe->carbs }} g
                </div>

                <!-- Favorite -->
                <form action="{{ route('recipes.favorite', $recipe->id) }}" method="POST" class="mt-3">
                    @csrf
                    @if(auth()->user()?->favorites->contains($recipe->id))
                        <button class="btn btn-success px-4">✓ Favorited</button>
                    @else
                        <button class="btn btn-outline-success px-4">+ Favorite</button>
                    @endif
                </form>

                <div class="section-title">Ingredients</div>
                <div class="ingredients-box">
                    <ul>
                        @foreach(explode("\n", trim($recipe->ingredients)) as $item)
                            @if(strlen(trim($item)) > 0)
                                <li>{{ trim($item) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <div class="section-title">Step-by-step</div>
                <div class="steps-box">
                    <p class="desc-text">{!! nl2br(e($recipe->instructions)) !!}</p>
                </div>

            </div>

            <!-- IMAGE -->
            <div class="col-md-5">
                <img src="{{ asset($recipe->image) }}" class="hero-img">
            </div>

        </div>

        <!-- COMMENTS -->
        <h2 class="section-title mt-5">Comments & Reviews</h2>
        <hr>

        <div class="d-flex align-items-center gap-3 mb-3">

            <!-- SORT -->
            <select id="sortSelect" class="form-select w-auto" onchange="applyFilters()">
                <option value="newest"  {{ request('sort','newest')=='newest'?'selected':'' }}>Newest</option>
                <option value="oldest"  {{ request('sort')=='oldest'?'selected':'' }}>Oldest</option>
                <option value="liked"   {{ request('sort')=='liked'?'selected':'' }}>Most Liked</option>
                <option value="disliked"{{ request('sort')=='disliked'?'selected':'' }}>Least Liked</option>
                <option value="highest_rating" {{ request('sort')=='highest_rating'?'selected':'' }}>Highest Rating</option>
                <option value="lowest_rating"  {{ request('sort')=='lowest_rating'?'selected':'' }}>Lowest Rating</option>
            </select>

            <!-- RATING FILTER -->
            <select id="ratingSelect" class="form-select w-auto" onchange="applyFilters()">
                <option value="">All Ratings</option>
                @for($i=5; $i>=1; $i--)
                    <option value="{{ $i }}" {{ request('rating')==$i?'selected':'' }}>
                        {{ $i }} Stars
                    </option>
                @endfor
            </select>

        </div>

        <hr>

        <!-- COMMENT FORM -->
        @auth
        @if(!\App\Models\Comment::userHasCommented($recipe->id, auth()->id()))
        <form action="{{ route('comments.store',$recipe->id) }}" method="POST" class="mb-4">
            @csrf

            <div id="star-rating">
                @for($i=1;$i<=5;$i++)
                    <span data-value="{{ $i }}">★</span>
                @endfor
            </div>

            <input type="hidden" name="rating" id="rating-value">

            <textarea name="content" class="form-control mt-2" placeholder="Write your comment..." required></textarea>

            <button class="btn btn-success mt-2 px-4">Submit</button>
        </form>
        @endif
        @endauth

        <!-- COMMENTS LOOP -->
        @foreach($comments as $comment)
            @include('recipes.partials.comment', ['comment'=>$comment,'recipe'=>$recipe])
        @endforeach

        <div class="mt-4">
            {{ $comments->links('vendor.pagination.simple-numbers') }}
        </div>

    </div>
</div>

<x-footer />

<div id="toast"></div>

<script src="{{ asset('bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/recipe-show.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
