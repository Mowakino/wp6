<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Leaf Spoon</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Local Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('bootstrap-5.0.2-dist/css/bootstrap.min.css') }}">

    <style>
        body {
            background: #f4f7f3;
            font-family: 'Poppins', sans-serif;
        }
        .login-card {
            max-width: 420px; /* WIDER */
            width: 100%;
            margin: 60px auto;
            border-radius: 16px;
            padding: 40px; /* slightly more padding */
            background: #fff;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }
        .brand-img {
            width: 150px;
            margin-bottom: 25px;
        }
        .login-btn {
            background: #8db36b;
            border: none;
            color: white !important; /* WHITE TEXT */
            font-weight: 600;
        }
        .login-btn:hover {
            background: #7ba45f;
            color: white;
        }
        a {
            color: #84a85c;
        }
        .form-control {
            border-radius: 10px;
        }

    </style>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">

    <div class="login-card text-center">

        {{-- Logo --}}
        <img src="{{ asset('vector.png') }}" class="brand-img" alt="Logo">

        {{-- Validation errors --}}
        @if ($errors->any())
            <p class="text-danger fw-semibold mb-2">{{ $errors->first() }}</p>
        @endif

        {{-- Login failed --}}
        @if (session('error'))
            <p class="text-danger fw-semibold mb-2">{{ session('error') }}</p>
        @endif

        {{-- Success message --}}
        @if (session('success'))
            <p class="text-success fw-semibold mb-2">{{ session('success') }}</p>
        @endif



        {{-- Login Form --}}
        <form action="/login" method="POST" class="mt-3">
            @csrf

            <div class="mb-3 text-start">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <div class="mb-3 text-start">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" class="btn login-btn w-100 py-2">Log In</button>
        </form>

        <p class="mt-3 mb-0">
            Don’t have an account?
            <a href="/register" class="fw-semibold">Sign Up</a>
        </p>

    </div>

</div>

{{-- Local Bootstrap JS --}}
<script src="{{ asset('bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
