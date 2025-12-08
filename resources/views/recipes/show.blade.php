<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $recipe->name }} - Leaf & Spoon</title>

    <link rel="stylesheet" href="{{ asset('bootstrap-5.0.2-dist/css/bootstrap.min.css') }}">

<style>
body {
    background: #fafafa;
    font-family: Arial, sans-serif;
}

.wrapper {
    width: 92%;
    max-width: 1200px;
    margin: auto;
}

/* ================= SKELETON ================= */
.skeleton {
    background: #e3e3e3;
    border-radius: 8px;
    animation: pulse 1.3s infinite;
}
@keyframes pulse {
    0% { opacity: .75; }
    50% { opacity: .35; }
    100% { opacity: .75; }
}

.skel-img { width:100%; height:350px; border-radius:20px; }
.skel-title { width:60%; height:34px; margin:16px 0; }
.skel-line { width:100%; height:14px; margin:10px 0; border-radius:6px; }

/* ================= HEADER ================= */
.recipe-title {
    font-size: 42px;
    font-weight: 700;
    line-height: 1.2;
}

.meta-row span {
    font-size: 15px;
    opacity: .85;
}

.badge-diff {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
}
.diff-easy { background:#dff6dd;color:#2e7d32; }
.diff-medium { background:#fff1c6;color:#8a6a00; }
.diff-hard { background:#ffd7d7;color:#c62828; }

.hero-img {
    width: 100%;
    height: 350px;
    border-radius: 20px;
    object-fit: cover;
    box-shadow: 0 6px 20px rgba(0,0,0,.13);
}

.desc-text {
    font-size: 16px;
    line-height: 1.55;
    color: #444;
}

.nutrition-box {
    background: #f2f2f2;
    padding: 18px 22px;
    border-radius: 12px;
    margin-top: 16px;
    width: fit-content;
}

/* ================= COMMENTS ================= */
.comment {
    padding: 18px 0;
    border-bottom: 1px solid #ddd;
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #77A169;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
}

.avatar-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.avatar-img-small {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
}

.vote-btn {
    background: none;
    border: none;
    cursor: pointer;
    opacity: .8;
}
.vote-btn:hover { opacity: 1; }

.reply-toggle { 
    cursor: pointer;
    color: #4CAF50;
    background: none;
    border: none;
    opacity: .9;
}
.reply-toggle:hover { opacity: 1; }

.reply-box {
    max-height: 0;
    overflow: hidden;
    transition: .25s ease;
    margin-left: 55px;
}

/* STAR RATING */
#star-rating span {
    font-size: 30px;
    cursor: pointer;
    color: #aaa;
}
#star-rating span.selected {
    color: #ffcc00;
}

/* ================= TOAST ================= */
#toast {
    position: fixed;
    right: 25px;
    bottom: 25px;
    background: #4CAF50;
    color: #fff;
    padding: 14px 22px;
    border-radius: 8px;
    opacity: 0;
    transform: translateY(20px);
    transition: .35s;
    z-index: 9999;
}
#toast.show {
    opacity: 1;
    transform: translateY(0);
}
</style>
</head>

<body>

<x-navbar />

<div class="wrapper">

    <!-- ================= SKELETON ================= -->
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


    <!-- ================= ACTUAL CONTENT ================= -->
    <div id="content" style="display:none;">

        <div class="row gx-5 mt-5">

            <!-- LEFT -->
            <div class="col-md-7">

                <h1 class="recipe-title">{{ $recipe->name }}</h1>

                <!-- META -->
                <div class="meta-row d-flex flex-wrap gap-2 mt-2">
                    <span class="d-flex align-items-center gap-1">
                        <img src="{{ asset('svg/clock.svg') }}" alt="time" style="width:16px; height:16px; opacity:.8;">
                        {{ $recipe->time_minutes }} min
                    </span>

                    <span>•</span>

                    @if($recipe->difficulty == 'Easy')
                        <span class="badge-diff diff-easy">Easy</span>
                    @elseif($recipe->difficulty == 'Medium')
                        <span class="badge-diff diff-medium">Medium</span>
                    @else
                        <span class="badge-diff diff-hard">Hard</span>
                    @endif

                    <span>•</span>

                    <span class="badge-diff" style="background:#e6f6ec;color:#2d6a4f;">
                        {{ $recipe->cuisine }}
                    </span>

                    <span>•</span>

                    <span>Rating: {{ number_format($recipe->average_rating, 1) }} ★</span>
                </div>

                <!-- By User -->
                <div class="mt-3 text-muted" style="font-size: 15px;">
                    by <strong>{{ $recipe->user->name }}</strong>
                </div>

                <!-- DESCRIPTION -->
                <p class="desc-text mt-3">{{ $recipe->description }}</p>

                <!-- NUTRITION BOX -->
                <div class="nutrition-box">
                    <strong>Nutrition Per Serving</strong><br>
                    Calories: {{ $recipe->calories }} kcal<br>
                    Protein: {{ $recipe->protein }} g<br>
                    Fat: {{ $recipe->fat }} g<br>
                    Carbs: {{ $recipe->carbs }} g
                </div>

                <!-- FAVORITE BUTTON -->
                <form action="{{ route('recipes.favorite', $recipe->id) }}" method="POST" class="mt-3">
                    @csrf

                    @if(auth()->user()->favorites->contains($recipe->id))
                        <button class="btn btn-success px-4">✓ Favorited</button>
                    @else
                        <button class="btn btn-outline-success px-4">+ Favorite</button>
                    @endif
                </form>

                <!-- INGREDIENTS -->
                <div class="section-title">Ingredients</div>
                <p class="desc-text">{!! nl2br(e($recipe->ingredients)) !!}</p>

                <!-- INSTRUCTIONS -->
                <div class="section-title">Step-by-step</div>
                <p class="desc-text">{!! nl2br(e($recipe->instructions)) !!}</p>

            </div>


            <!-- RIGHT IMAGE -->
            <div class="col-md-5">
                <img src="{{ asset($recipe->image) }}" class="hero-img" alt="">
            </div>

        </div>


        <!-- COMMENTS -->
        <h2 class="section-title mt-5">Comments & Reviews</h2>

        <hr class="my-4">

        {{-- SORTING TABS --}}
        <div class="d-flex align-items-center gap-4 mb-3">

            <a href="?sort=newest"
            class="{{ request('sort','newest')=='newest' ? 'fw-bold text-success' : 'text-secondary' }}">
                Newest
            </a>

            <a href="?sort=oldest"
            class="{{ request('sort')=='oldest' ? 'fw-bold text-success' : 'text-secondary' }}">
                Oldest
            </a>

            <a href="?sort=liked"
            class="{{ request('sort')=='liked' ? 'fw-bold text-success' : 'text-secondary' }}">
                Most Liked
            </a>

        </div>

        <hr class="mb-4">


        @auth
        <form action="{{ route('comments.store',$recipe->id) }}" method="POST" class="mb-4">
            @csrf

            <div class="d-flex align-items-start gap-3">

                {{-- USER AVATAR --}}
                @if(Auth::user()->profile_image)
                    <img src="{{ asset(Auth::user()->profile_image) }}" class="avatar-img">
                @else
                    <div class="avatar">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
                @endif

                <div class="flex-grow-1">

                    {{-- STAR SELECTOR --}}
                    <div id="star-rating" class="mb-2">
                        <span data-value="1">★</span>
                        <span data-value="2">★</span>
                        <span data-value="3">★</span>
                        <span data-value="4">★</span>
                        <span data-value="5">★</span>
                    </div>

                    <input type="hidden" name="rating" id="rating-value">

                    {{-- COMMENT INPUT --}}
                    <textarea name="content"
                            class="form-control mt-1"
                            placeholder="Write your comment..."
                            required></textarea>

                    {{-- SUBMIT BUTTON --}}
                    <button class="btn btn-success mt-2 px-4">Submit</button>

                </div>
            </div>

        </form>
        @endauth


        @foreach($comments as $comment)
        <div class="comment">

            <div class="d-flex align-items-center gap-3">

                {{-- AVATAR FOR COMMENTS --}}
                @if($comment->user->profile_image)
                    <img src="{{ asset($comment->user->profile_image) }}" class="avatar-img">
                @else
                    <div class="avatar">{{ strtoupper(substr($comment->user->name,0,1)) }}</div>
                @endif

                <div>
                    <strong>{{ $comment->user->name }}</strong><br>
                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                </div>
            </div>

            @if($comment->given_rating)
                <div class="mt-2 text-warning" style="font-size: 18px;">
                    {{ str_repeat("★", $comment->given_rating) }}
                </div>
            @endif


            <p class="mt-2">{{ $comment->content }}</p>

            <div class="d-flex gap-3 mt-2 align-items-center">

                <form method="POST" action="{{ route('comments.like',$comment->id) }}">
                    @csrf
                    <button class="vote-btn">👍 {{ $comment->likes }}</button>
                </form>

                <form method="POST" action="{{ route('comments.dislike',$comment->id) }}">
                    @csrf
                    <button class="vote-btn">👎 {{ $comment->dislikes }}</button>
                </form>

                @auth
                <button class="reply-toggle" onclick="toggleReply({{ $comment->id }})">
                    Reply
                </button>

                {{-- EDIT / DELETE ONLY FOR OWNER (TOP LEVEL) --}}
                @if(auth()->id() === $comment->user_id)
                    <button class="btn btn-link p-0 ms-2" 
                            style="font-size: 14px;"
                            onclick="toggleEdit({{ $comment->id }})"
                            type="button">
                        Edit
                    </button>

                    <form method="POST" 
                          action="{{ route('comments.destroy', $comment->id) }}"
                          onsubmit="return confirm('Delete this comment?');"
                          class="ms-1">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-link text-danger p-0" style="font-size: 14px;">
                            Delete
                        </button>
                    </form>
                @endif
                @endauth

            </div>

            {{-- EDIT BOX --}}
            @auth
            @if(auth()->id() === $comment->user_id)
                <form id="edit-{{ $comment->id }}" 
                    class="reply-box mt-2"
                    style="max-height:0;"
                    method="POST"
                    action="{{ route('comments.update', $comment->id) }}">
                    @csrf
                    @method('PATCH')

                    <textarea name="content" class="form-control mb-2" required>{{ $comment->content }}</textarea>

                    {{-- ⭐ EDITABLE STAR RATING (TOP-LEVEL ONLY) --}}
                    @if(!$comment->parent_id)
                        @php
                            $existingRating = $comment->recipe
                                ->ratings()
                                ->where('user_id', $comment->user_id)
                                ->first()
                                ->rating ?? 0;
                        @endphp

                        <label class="mb-1 d-block">Edit Rating:</label>

                        <div class="edit-stars mb-2" data-target="edit-rating-{{ $comment->id }}">
                            @for($i=1; $i<=5; $i++)
                                <span class="edit-star" 
                                    data-value="{{ $i }}" 
                                    style="font-size:26px; cursor:pointer; color: {{ $i <= $existingRating ? '#ffcc00' : '#aaa' }};">
                                    ★
                                </span>
                            @endfor
                        </div>

                        <input type="hidden" 
                            name="rating" 
                            id="edit-rating-{{ $comment->id }}" 
                            value="{{ $existingRating }}">
                    @endif

                    <button class="btn btn-success btn-sm mt-1">Save</button>
                </form>
            @endif
            @endauth


            {{-- REPLY BOX --}}
            @auth
            <form id="reply-{{ $comment->id }}" class="reply-box mt-2 reply-area"
                action="{{ route('comments.store',$recipe->id) }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <textarea name="content" class="form-control mb-2" required></textarea>
                <button class="btn btn-success btn-sm">Send</button>
            </form>
            @endauth

            {{-- REPLIES --}}
            @foreach($comment->replies as $reply)
            <div class="mt-3 ms-5 border-start ps-3">

                <div class="d-flex align-items-center gap-2">

                    @if($reply->user->profile_image)
                        <img src="{{ asset($reply->user->profile_image) }}" class="avatar-img-small">
                    @else
                        <div class="avatar small">{{ strtoupper(substr($reply->user->name,0,1)) }}</div>
                    @endif

                    <div>
                        <strong>{{ $reply->user->name }}</strong><br>
                        <small>{{ $reply->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                <p class="mt-2">{{ $reply->content }}</p>

            </div>
            @endforeach

        </div>
        @endforeach

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $comments->links('pagination::bootstrap-5') }}
        </div>

    </div>

</div>

<x-footer />

<div id="toast"></div>

<script src="{{ asset('bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') }}"></script>

<script>

/* EDIT STAR RATING */
document.querySelectorAll('.edit-stars').forEach(wrapper => {
    const inputId = wrapper.dataset.target;
    const input = document.getElementById(inputId);
    const stars = wrapper.querySelectorAll('.edit-star');

    stars.forEach(star => {
        star.addEventListener('mouseover', () => {
            highlightEdit(stars, star.dataset.value);
        });
        star.addEventListener('mouseout', () => {
            highlightEdit(stars, input.value);
        });
        star.addEventListener('click', () => {
            input.value = star.dataset.value;
            highlightEdit(stars, input.value);
        });
    });
});

function highlightEdit(stars, value) {
    stars.forEach(s => {
        s.style.color = s.dataset.value <= value ? '#ffcc00' : '#aaa';
    });
}

/* REPLY TOGGLE */
function toggleReply(id){
    let box = document.getElementById("reply-"+id);
    box.style.maxHeight = box.style.maxHeight ? null : box.scrollHeight + "px";
}

/* EDIT TOGGLE */
function toggleEdit(id){
    let box = document.getElementById("edit-"+id);
    box.style.maxHeight = box.style.maxHeight ? null : box.scrollHeight + "px";
}

/* STAR RATING */
const stars = document.querySelectorAll('#star-rating span');
const ratingInput = document.getElementById('rating-value');

stars.forEach(star => {
    star.addEventListener('mouseover', function () {
        highlightStars(this.dataset.value);
    });
    star.addEventListener('mouseout', function () {
        highlightStars(ratingInput.value);
    });
    star.addEventListener('click', function () {
        ratingInput.value = this.dataset.value;
        highlightStars(this.dataset.value);
    });
});

function highlightStars(value) {
    stars.forEach(star => {
        star.classList.toggle("selected", star.dataset.value <= value);
        star.style.color = (star.dataset.value <= value) ? '#ffcc00' : '#aaa';
    });
}

/* TOAST SYSTEM */
function toast(msg,type="success"){
    const t=document.getElementById("toast");
    t.innerText=msg;x
    t.style.background = type==="error" ? "#e74c3c" : "#4CAF50";
    t.classList.add("show");
    setTimeout(()=> t.classList.remove("show"),2500);
}

@if(session("success"))
    toast("{{ session('success') }}");
@endif
@if(session("error"))
    toast("{{ session('error') }}","error");
@endif

/* SKELETON LOADER */
setTimeout(()=>{
    document.getElementById("skeleton").style.display="none";
    document.getElementById("content").style.display="block";
},600);

</script>

</body>
</html>
