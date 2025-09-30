<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CRM Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Optional: Tailwind CSS -->
    @vite('resources/css/app.css')
</head>
<body class="bg-light min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">CRM</a>

            <div class="d-flex align-items-center ms-auto">
                @auth
                    <span class="me-3">{{ auth()->user()->name }} ({{ auth()->user()->email }})</span>

                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm me-2">Profile</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">Log Out</button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm me-2">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary btn-sm">Register</a>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS (optional, needed for dropdowns etc) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
