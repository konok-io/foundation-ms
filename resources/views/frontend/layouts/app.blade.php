<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Home' }} - {{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: {{ GeneralSetting::getSetting('primary_color', '#4F46E5') }};
            --secondary-color: {{ GeneralSetting::getSetting('secondary_color', '#10B981') }};
        }
        body {
            font-family: 'Inter', sans-serif;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .text-primary {
            color: var(--primary-color) !important;
        }
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40" class="me-2">
                <span class="fw-bold">{{ GeneralSetting::getSetting('site_name', 'Foundation') }}</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2" href="{{ route('login') }}">Login</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5>{{ GeneralSetting::getSetting('site_name', 'Foundation') }}</h5>
                    <p class="text-muted mb-0">{{ GeneralSetting::getSetting('site_tagline', '') }}</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h6>Contact</h6>
                    <p class="text-muted mb-1">
                        <i class="bi bi-envelope me-2"></i>{{ GeneralSetting::getSetting('email', '') }}
                    </p>
                    <p class="text-muted mb-0">
                        <i class="bi bi-phone me-2"></i>{{ GeneralSetting::getSetting('phone', '') }}
                    </p>
                </div>
                <div class="col-md-4">
                    <h6>Follow Us</h6>
                    <div class="d-flex gap-3">
                        @if(GeneralSetting::getSetting('facebook'))
                        <a href="{{ GeneralSetting::getSetting('facebook') }}" class="text-white"><i class="bi bi-facebook fs-5"></i></a>
                        @endif
                        @if(GeneralSetting::getSetting('twitter'))
                        <a href="{{ GeneralSetting::getSetting('twitter') }}" class="text-white"><i class="bi bi-twitter fs-5"></i></a>
                        @endif
                        @if(GeneralSetting::getSetting('instagram'))
                        <a href="{{ GeneralSetting::getSetting('instagram') }}" class="text-white"><i class="bi bi-instagram fs-5"></i></a>
                        @endif
                        @if(GeneralSetting::getSetting('youtube'))
                        <a href="{{ GeneralSetting::getSetting('youtube') }}" class="text-white"><i class="bi bi-youtube fs-5"></i></a>
                        @endif
                    </div>
                </div>
            </div>
            <hr class="my-3">
            <p class="text-center text-muted mb-0">&copy; {{ date('Y') }} {{ GeneralSetting::getSetting('site_name', 'Foundation') }}. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
