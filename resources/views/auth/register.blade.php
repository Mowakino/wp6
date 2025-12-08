<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - Leaf Spoon</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('bootstrap-5.0.2-dist/css/bootstrap.min.css') }}">

    <style>
        body {
            background: #f4f7f3;
            font-family: 'Poppins', sans-serif;
        }
        .register-card {
            max-width: 420px;
            margin: 50px auto;
            border-radius: 16px;
            padding: 35px;
            background: #fff;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }
        .brand-img {
            width: 150px;
            margin-bottom: 25px;
        }
        .register-btn {
            background: #8db36b;
            border: none;
            color: white !important;
            font-weight: 600;
        }
        .register-btn:hover {
            background: #7ba45f;
        }
        .error-text {
            color: #d32f2f;
            font-size: 13px;
            margin-top: 4px;
        }
    </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">

    <div class="register-card text-center">

        {{-- Logo --}}
        <img src="{{ asset('vector.png') }}" class="brand-img">

        {{-- Error: top-level messages --}}
        @if ($errors->any())
            <p class="text-danger fw-semibold mb-2">
                {{ $errors->first() }}
            </p>
        @endif

        {{-- Success message --}}
        @if (session('success'))
            <p class="text-success fw-semibold mb-2">
                {{ session('success') }}
            </p>
        @endif

        {{-- REGISTER FORM --}}
        <form action="/register" method="POST">
            @csrf

            {{-- Name --}}
            <div class="mb-3 text-start">
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3 text-start">
                <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3 text-start">
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">Show</button>
                </div>
                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-3 text-start">
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">Show</button>
                </div>
            </div>

            <button type="submit" class="btn register-btn w-100 py-2 mt-2">Sign Up</button>
        </form>

        <p class="mt-3 mb-0">
            Already have an account?
            <a href="/login" class="fw-semibold" style="color:#84a85c;">Log In</a>
        </p>

    </div>

</div>

<script src="{{ asset('bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') }}"></script>
<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
