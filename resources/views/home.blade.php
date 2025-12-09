<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leaf & Spoon - Home</title>
    <link rel="stylesheet" href="{{ asset('bootstrap-5.0.2-dist/css/bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #ffffff;
            color: #333;
        }

        /* HERO */
        .hero {
            width: 100%;
            height: 420px;
            background-image: url('{{ asset("Description BG.png") }}');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            padding-left: 60px;
        }

        .hero-text {
            max-width: 380px;
            color: #fff;
        }

        .hero-text h1 {
            font-size: 42px;
            font-weight: 600;
        }

        .hero-text p {
            font-size: 18px;
            margin-top: 10px;
        }
        .discover-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 1.05rem;
            color: #ffffff !important;
            transition: color .25s ease, transform .25s ease;
        }

        .discover-link:hover {
            color: #eaeaea !important; /* putih sedikit gelap agar kontras tetap ada */
            transform: translateX(4px);
        }

        .section-title {
            margin-top: 60px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 32px;
            font-weight: 600;
        }

        .recipe-img {
            width: 100%;
            max-width: 430px;
            height: 300px;
            object-fit: cover;
            border-radius: 14px;
        }
        .carousel-control-prev-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='rgba(0,0,0,0.8)' viewBox='0 0 16 16'%3E%3Cpath d='M11.354 1.646a.5.5 0 0 1 0 .708L6.707 7l4.647 4.646a.5.5 0 1 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 0 1 .708 0z'/%3E%3C/svg%3E");
        }

        .carousel-control-next-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='rgba(0,0,0,0.8)' viewBox='0 0 16 16'%3E%3Cpath d='M4.646 1.646a.5.5 0 0 1 .708 0l5 5a.5.5 0 0 1 0 .708l-5 5a.5.5 0 1 1-.708-.708L9.293 7 4.646 2.354a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        }
        .section-title {
            margin-top: 50px;
            text-align: center;
            font-size: 36px;
            font-weight: 600;
        }
        .recipe-desc {
            display: -webkit-box;
            -webkit-line-clamp: 4; /* jumlah baris maksimum */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .recipe-card-container {
            width: 80%;
            background: #fff;
            border: 1px solid rgba(0,0,0,0.10);
            border-radius: 18px;
            padding: 45px 55px;
            box-shadow: 0 6px 26px rgba(0,0,0,0.08);
            transition: box-shadow .25s ease, transform .25s ease;
        }

        .recipe-card-container:hover {
            box-shadow: 0 10px 36px rgba(0,0,0,0.12);
            transform: translateY(-3px);
        }

        .carousel-item {
            padding: 40px 0;
        }

    </style>
</head>

<body>

    {{-- NAVBAR --}}
    <x-navbar />

    {{-- HERO --}}
    <div class="hero">
        <div class="hero-text">
            <h1>Discover the art of healthy eating</h1>
            <a href="{{ route('recipes.index') }}" class="discover-link fw-bold text-decoration-none">
            Discover More →
            </a>
        </div>
    </div>

    {{-- TITLE --}}
    <h2 class="section-title">Discover the Recipe Everyone’s Talking About</h2>
    <hr class="mx-auto" style="width: 85%; margin-top: 10px; margin-bottom: 40px; border-top: 1px solid #ccc;">


    {{-- CAROUSEL --}}
    <div id="recipeCarousel" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-inner">
            @foreach($recipes as $i => $recipe)
            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">

                <div class="container recipe-card-container" style="width: 75%">
                    <div class="row align-items-center">

                        {{-- LEFT TEXT AREA --}}
                        <div class="col-md-6">

                            {{-- Number (01, 02, 03...) --}}
                            <p class="text-success fw-bold mb-1" style="font-size: 18px;">
                                {{ sprintf('%02d', $i+1) }}
                            </p>

                            {{-- Recipe Title --}}
                            <h2 class="fw-bold" style="font-size: 36px; line-height: 1.2;">
                                {{ $recipe->name }}
                            </h2>
                            {{-- Rating Stars --}}
                            <div class="mt-2 mb-3">
                                @php $rounded = round($recipe->average_rating); @endphp
                                @for ($s = 1; $s <= 5; $s++)
                                    <span style="font-size: 20px;"
                                        class="{{ $s <= $rounded ? 'text-warning' : 'text-muted' }}">
                                        ★
                                    </span>
                                @endfor

                                {{-- Show numeric rating --}}
                                <span class="text-muted ms-2" style="font-size: 16px;">
                                    {{ $recipe->average_rating }}/5.0
                                </span>
                            </div>
                            {{-- Description --}}
                            <p class="text-muted mt-3 recipe-desc">
                                {{ Str::limit($recipe->description, 160) }}
                            </p>
                            {{-- Buttons --}}
                            <div class="mt-4 d-flex gap-3">

                                {{-- Favorites Button --}}
                                <form action="{{ route('recipes.favorite', $recipe->id) }}" method="POST">
                                    @csrf

                                    @if(auth()->user()->favorites->contains($recipe->id))
                                        <button class="btn btn-success px-4">✓ Favorited</button>
                                    @else
                                        <button class="btn btn-outline-success px-4">+ Favorite</button>
                                    @endif
                                </form>

                                {{-- Learn More --}}
                                <a href="{{ route('recipes.show', $recipe->id) }}"
                                class="btn btn-link text-dark fw-semibold"
                                style="text-decoration: none;">
                                    Learn More →
                                </a>
                            </div>

                        </div>

                        {{-- RIGHT IMAGE --}}
                        <div class="col-md-6 text-center">
                            <img src="{{ asset($recipe->image) }}"
                                class="rounded"
                                style="width: 100%; max-width: 460px; height: 340px; object-fit: cover;">
                        </div>

                    </div>
                </div>

            </div>
            @endforeach
        </div>

        {{-- CONTROLS --}}
        <button class="carousel-control-prev" type="button"
                data-bs-target="#recipeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button"
                data-bs-target="#recipeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>

    {{-- FOOTER --}}
    <x-footer />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="{{ asset('bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
