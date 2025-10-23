<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CRM Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Optional: Tailwind CSS -->
    @vite('resources/css/app.css')

    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 700;
        }
        .hover-scale {
            transition: all 0.3s ease;
        }
        .hover-scale:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 20px rgba(0,0,0,0.12);
        }
    </style>
</head>
<body class="min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">CRM</a>

            <div class="d-flex align-items-center ms-auto">
                @auth
                    <span class="me-3">{{ auth()->user()->name }} ({{ auth()->user()->email }})</span>

                    <a href="{{ route('profile.index') }}" class="btn btn-outline-primary btn-sm me-2">Profile</a>

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
