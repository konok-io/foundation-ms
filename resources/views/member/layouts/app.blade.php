<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Member Portal' }} - {{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: {{ \App\Models\GeneralSetting::getSetting('primary_color', '#4F46E5') }};
            --secondary-color: {{ \App\Models\GeneralSetting::getSetting('secondary_color', '#10B981') }};
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f5;
        }
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #764ba2 100%);
        }
        .sidebar {
            background: white;
            min-height: calc(100vh - 56px);
            padding-top: 20px;
            border-right: 1px solid #eee;
        }
        .sidebar .nav-link {
            color: #333;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            background: #f0f0ff;
            color: var(--primary-color);
        }
        .sidebar .nav-link.active {
            background: var(--primary-color);
            color: white;
        }
        .sidebar .nav-link i {
            width: 24px;
        }
        .main-content {
            padding: 25px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }
        .text-primary {
            color: var(--primary-color) !important;
        }
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        .profile-card {
            text-align: center;
            padding: 30px;
        }
        .profile-card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 4px solid var(--primary-color);
        }
        @media (max-width: 991px) {
            .sidebar {
                min-height: auto;
                border-right: none;
                border-bottom: 1px solid #eee;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('member.dashboard') }}">
                <i class="bi bi-shield-check me-2"></i>
                <span>Member Portal</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#memberNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="memberNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->avatar_url ?? asset('images/avatar.png') }}" alt="" class="rounded-circle me-2" width="30" height="30">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('member.profile') }}"><i class="bi bi-person me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.change-password') }}"><i class="bi bi-key me-2"></i>Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-md-3">
                <nav class="sidebar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('member.dashboard') }}" class="nav-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('member.profile') }}" class="nav-link {{ request()->routeIs('member.profile') ? 'active' : '' }}">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('member.card') }}" class="nav-link {{ request()->routeIs('member.card') ? 'active' : '' }}">
                                <i class="bi bi-credit-card"></i>
                                <span>ID Card</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('member.contributions') }}" class="nav-link {{ request()->routeIs('member.contributions') ? 'active' : '' }}">
                                <i class="bi bi-wallet2"></i>
                                <span>Contributions</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('member.emergency-collections') }}" class="nav-link {{ request()->routeIs('member.emergency-collections') ? 'active' : '' }}">
                                <i class="bi bi-exclamation-triangle"></i>
                                <span>Emergency</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('member.payments') }}" class="nav-link {{ request()->routeIs('member.payments') ? 'active' : '' }}">
                                <i class="bi bi-cash-stack"></i>
                                <span>Payments</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('member.donations') }}" class="nav-link {{ request()->routeIs('member.donations') ? 'active' : '' }}">
                                <i class="bi bi-heart"></i>
                                <span>Donations</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('member.notices') }}" class="nav-link {{ request()->routeIs('member.notices') ? 'active' : '' }}">
                                <i class="bi bi-megaphone"></i>
                                <span>Notices</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('member.change-password') }}" class="nav-link {{ request()->routeIs('member.change-password') ? 'active' : '' }}">
                                <i class="bi bi-key"></i>
                                <span>Change Password</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            
            <div class="col-lg-10 col-md-9">
                <div class="main-content">
                    @include('partials.alerts')
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
