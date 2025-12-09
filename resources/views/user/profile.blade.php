<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $user->name }} - Profile | Leaf & Spoon</title>

    <link rel="stylesheet" href="{{ asset('bootstrap-5.0.2-dist/css/bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background: #fafafa;
            font-family: "Nunito Sans", sans-serif;
        }

        .profile-wrapper {
            width: 92%;
            max-width: 1200px;
            margin: auto;
            padding-top: 40px;
        }

        .recipe-card {
            background: white;
            border-radius: 14px;
            padding: 18px;
            display: flex;
            gap: 18px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: .2s;
            position: relative;
            z-index: 0;
        }
        .recipe-card:hover { transform: translateY(-5px); }

        .recipe-img {
            width: 180px;
            height: 140px;
            border-radius: 12px;
            object-fit: cover;
        }

        .recipe-title { font-size: 20px; font-weight: 700; }

        .time { font-size: 14px; opacity: .8; }

        .profile-side {
            border-left: 2px solid #ccc;
            padding-left: 30px;
        }

        .avatar-big {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 4px solid #8CB369;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 58px;
            color: #8CB369;
        }

        .avatar-big-img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #8CB369;
        }

        .no-recipes-box {
            background: #eef5e9;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin-top: 20px;
            color: #6a8750;
            font-size: 18px;
        }

        /* ACTION BUTTONS FIXED */
        .action-links {
            position: absolute;
            top: 12px;
            right: 14px;
            display: flex;
            gap: 16px;
            align-items: center;
            z-index: 10; /* keeps above EVERYTHING */
            background: rgba(255,255,255,0.85);
            padding: 4px 8px;
            border-radius: 8px;
            backdrop-filter: blur(6px);
        }

        .icon-btn {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 14px;
            color: #2d6a4f;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            text-decoration: none;
        }

        .icon-btn:hover { opacity: 0.7; }

        .icon {
            width: 18px;
            height: 18px;
        }

        /* DELETE ICON SWITCH ON HOVER */
        .delete-btn:hover .delete-icon {
            content: url('/svg/delete-fill.svg');
        }

        .icon-btn.text-danger { color: #c0392b !important; }
    </style>
</head>

<body>

<x-navbar />

<main class="flex-grow-1">

<div class="profile-wrapper">

    <div class="row">

        <!-- LEFT CONTENT -->
        <div class="col-md-8">
            <h1 class="fw-bold">{{ $user->name }}</h1>
            <hr>

            @if ($recipes->isEmpty())

                <div class="no-recipes-box">
                    <p>You haven't created any recipes yet.</p>
                    <a href="{{ route('recipes.create') }}" class="btn btn-success px-4 mt-3">
                        + Create Your First Recipe
                    </a>
                </div>

            @else

                @foreach($recipes as $recipe)
                <div class="recipe-card-wrapper position-relative">

                    <!-- ACTION BUTTONS -->
                    <div class="action-links">

                        <!-- EDIT -->
                        <a href="{{ route('recipes.edit', $recipe->id) }}" class="icon-btn">
                            <img src="{{ asset('svg/edit.svg') }}" class="icon">
                            <span>Edit</span>
                        </a>

                        <!-- DELETE -->
                        <form action="{{ route('recipes.destroy', $recipe->id) }}"
                              method="POST"
                              onsubmit="return confirm('Delete this recipe?');">

                            @csrf
                            @method('DELETE')

                            <button class="icon-btn text-danger delete-btn">
                                <img src="{{ asset('svg/delete.svg') }}" class="icon delete-icon">
                                <span>Delete</span>
                            </button>
                        </form>

                    </div>

                    <!-- RECIPE CARD -->
                    <a href="{{ route('recipes.show', $recipe->id) }}"
                       class="recipe-card text-decoration-none text-dark">

                        <img class="recipe-img" src="{{ asset($recipe->image) }}" alt="recipe">

                        <div>
                            <div class="recipe-title">{{ $recipe->name }}</div>

                            <div class="time mt-1">
                                {{ $recipe->time_minutes }} Min
                                <img src="{{ asset('svg/clock.svg') }}" style="width: 14px; opacity: .7;">
                            </div>

                            <div class="mt-2" style="font-size: 14px;">
                                Ratings: {{ number_format($recipe->rating,1) }} ★<br>
                                Calories: {{ $recipe->calories }} kcal<br>
                                Protein: {{ $recipe->protein }}g<br>
                                Carbs: {{ $recipe->carbs }}g
                            </div>

                            <div class="mt-1 text-muted" style="font-size: 13px;">
                                {{ $recipe->created_at->format('d/m/Y') }}
                            </div>
                        </div>

                    </a>
                </div>
                @endforeach

                <!-- PAGINATION -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $recipes->links('vendor.pagination.simple-numbers') }}
                </div>

            @endif

        </div>

        <!-- RIGHT SIDEBAR -->
        <div class="col-md-4 profile-side">

            @if($user->profile_image)
                <img src="{{ asset($user->profile_image) }}" class="avatar-big-img">
            @else
                <div class="avatar-big">
                    <span>{{ strtoupper(substr($user->name,0,1)) }}</span>
                </div>
            @endif

            <p class="mt-3 text-muted">
                Joined {{ $user->created_at->format('d/m/Y') }}
            </p>

            @if($user->bio)
                <div class="mt-2 mb-3" style="color:#444; font-size:15px; line-height:1.5;">
                    {{ $user->bio }}
                </div>
            @else
                <p class="text-muted" style="font-size:14px;">No bio added.</p>
            @endif

            <a href="#" class="d-block mt-3 text-success" onclick="openProfileModal()">
                Edit Profile
            </a>


            <a href="{{ route('favorites.index') }}" class="d-block mt-2 text-success">
                View Favorites
            </a>

            <!-- LOGOUT -->
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button class="btn btn-link text-danger p-0 m-0"
                        style="text-decoration: none; font-weight: 600;">
                    Log Out
                </button>
            </form>

        </div>

    </div>
</div>

<!-- ================= EDIT PROFILE MODAL ================= -->
<div id="editProfileModal"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.55); backdrop-filter:blur(3px);
            z-index:9999; justify-content:center; align-items:center;">

    <div style="background:white; width:450px; padding:30px; border-radius:16px;
                box-shadow:0 8px 30px rgba(0,0,0,0.2); position:relative;">

        <!-- CLOSE BUTTON -->
        <button onclick="closeProfileModal()"
                style="position:absolute; top:12px; right:14px;
                       background:none; border:none; font-size:20px; opacity:.6;">
            ✕
        </button>

        <h3 class="mb-3">Edit Profile</h3>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- PHOTO -->
            <label class="form-label fw-bold">Profile Photo</label><br>
            <img src="{{ asset(Auth::user()->profile_image) }}"
                 style="width:80px; height:80px; border-radius:50%; object-fit:cover;">
            <input type="file" name="profile_image" class="form-control mt-2">

            <!-- NAME -->
            <div class="mt-3">
                <label class="form-label fw-bold">Name</label>
                <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
            </div>

            <!-- BIO -->
            <div class="mt-3">
                <label class="form-label fw-bold">Short Bio</label>
                <textarea name="bio" class="form-control" rows="3">{{ $user->bio }}</textarea>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary" onclick="closeProfileModal()">Cancel</button>
                <button class="btn btn-success">Save</button>
            </div>

        </form>
    </div>

</div>

</main>

<x-footer />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="{{ asset('bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') }}"></script>
<script>
function openProfileModal() {
    document.getElementById('editProfileModal').style.display = 'flex';
}

function closeProfileModal() {
    document.getElementById('editProfileModal').style.display = 'none';
}
</script>

</body>
</html>
