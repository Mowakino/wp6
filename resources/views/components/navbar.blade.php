<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">

        <a href="{{ route('home') }}" class="navbar-brand">
            <img src="{{ asset('vector.png') }}" style="height: 40px;">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">


                <li class="nav-item">
                    <a class="nav-link" href="{{ route('recipes.index') }}">Recipes</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('recipes.create') }}">Create</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about-us') }}">About</a>
                </li>

            </ul>

            <a href="{{ route('profile') }}" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                <img src="{{ asset(auth()->user()->profile_image) }}"
                    class="rounded-circle"
                    style="width: 35px; height: 35px; object-fit: cover;">

                <span>{{ auth()->user()->name }}</span>
            </a>

        </div>

    </div>
</nav>
