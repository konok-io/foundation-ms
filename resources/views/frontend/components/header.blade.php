<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Home' }} - {{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0E7490;
            --primary-dark: #0C5873;
            --secondary: #16A34A;
            --accent: #F59E0B;
            --dark: #0F172A;
            --light: #F8FAFC;
            --success: #22C55E;
            --danger: #EF4444;
            --gradient-primary: linear-gradient(135deg, #0E7490 0%, #16A34A 100%);
            --gradient-accent: linear-gradient(135deg, #F59E0B 0%, #EF4444 100%);
            --gradient-dark: linear-gradient(135deg, #0F172A 0%, #334155 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light);
            color: var(--dark);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
        }

        /* Top Header */
        .top-header {
            background: var(--dark);
            color: white;
            padding: 8px 0;
            font-size: 13px;
        }

        .top-header a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s;
        }

        .top-header a:hover {
            color: var(--accent);
        }

        .top-header .social-links a {
            margin-left: 12px;
            font-size: 14px;
        }

        /* Main Header */
        .main-header {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 15px 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
        }

        .main-header.scrolled {
            padding: 8px 0;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .main-header .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .main-header .logo-icon {
            width: 50px;
            height: 50px;
            background: var(--gradient-primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            transition: transform 0.3s;
        }

        .main-header.scrolled .logo-icon {
            width: 40px;
            height: 40px;
            font-size: 20px;
        }

        .main-header .brand-text {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 22px;
            color: var(--dark);
        }

        .main-header .brand-tagline {
            font-size: 11px;
            color: var(--primary);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Navigation */
        .main-header .nav-link {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 15px;
            color: var(--dark);
            padding: 10px 18px !important;
            position: relative;
            transition: color 0.3s;
        }

        .main-header .nav-link::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 18px;
            right: 18px;
            height: 2px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .main-header .nav-link:hover::after,
        .main-header .nav-link.active::after {
            transform: scaleX(1);
        }

        .main-header .nav-link:hover,
        .main-header .nav-link.active {
            color: var(--primary);
        }

        /* Mega Menu */
        .mega-menu {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.15);
            padding: 30px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            min-width: 600px;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .mega-menu-trigger:hover .mega-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .mega-menu .mega-menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .mega-menu-item {
            padding: 15px;
            border-radius: 12px;
            transition: all 0.3s;
            text-decoration: none;
        }

        .mega-menu-item:hover {
            background: var(--light);
        }

        .mega-menu-item .icon {
            width: 45px;
            height: 45px;
            background: var(--gradient-primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .mega-menu-item .title {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 14px;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .mega-menu-item .desc {
            font-size: 12px;
            color: #64748b;
        }

        /* Action Buttons */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-search {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            background: transparent;
            color: var(--dark);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .btn-search:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            transform: rotate(90deg);
        }

        .btn-donate {
            background: var(--gradient-accent);
            color: white;
            padding: 12px 28px;
            border-radius: 50px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 14px;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
        }

        .btn-donate:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(245, 158, 11, 0.4);
            color: white;
        }

        .btn-join {
            background: transparent;
            color: var(--primary);
            padding: 12px 24px;
            border-radius: 50px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 14px;
            border: 2px solid var(--primary);
            transition: all 0.3s;
        }

        .btn-join:hover {
            background: var(--primary);
            color: white;
        }

        /* Mobile Menu */
        @media (max-width: 991px) {
            .main-header {
                padding: 10px 0;
            }

            .mega-menu {
                position: static;
                transform: none;
                min-width: auto;
                box-shadow: none;
                padding: 15px;
                opacity: 1;
                visibility: visible;
                display: none;
            }

            .mega-menu-trigger:hover .mega-menu,
            .mega-menu-trigger.show .mega-menu {
                display: block;
            }

            .header-actions {
                display: none;
            }

            .mobile-actions {
                display: flex !important;
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 5px;
        }

        /* Animation Classes */
        .fade-up {
            animation: fadeUp 0.8s ease forwards;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex gap-4">
                        <a href="tel:+8801700000000"><i class="bi bi-phone me-1"></i> +880 1700-000000</a>
                        <a href="mailto:info@foundation.org"><i class="bi bi-envelope me-1"></i> info@foundation.org</a>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex justify-content-md-end align-items-center gap-3">
                        <div class="social-links">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-twitter-x"></i></a>
                            <a href="#"><i class="bi bi-youtube"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                        </div>
                        <span class="border-start border-secondary opacity-25" style="height: 20px;"></span>
                        <a href="{{ route('login') }}" class="text-white-50"><i class="bi bi-person me-1"></i> Member Login</a>
                        <a href="{{ route('admin.dashboard') }}" class="text-white-50"><i class="bi bi-shield-lock me-1"></i> Admin</a>
                        <div class="dropdown">
                            <a class="dropdown-toggle text-white-50" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-translate me-1"></i> EN
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('language.switch', 'en') }}">English</a></li>
                                <li><a class="dropdown-item" href="{{ route('language.switch', 'bn') }}">বাংলা</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header sticky-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg p-0">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <div class="logo-icon">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <div>
                        <div class="brand-text">{{ $siteSettings['site_name'] ?? 'Foundation MS' }}</div>
                        <div class="brand-tagline">{{ $siteSettings['site_tagline'] ?? 'Building a Better Tomorrow' }}</div>
                    </div>
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                        </li>
                        
                        <li class="nav-item mega-menu-trigger">
                            <a class="nav-link" href="#">About <i class="bi bi-chevron-down ms-1" style="font-size: 10px;"></i></a>
                            <div class="mega-menu">
                                <div class="mega-menu-grid">
                                    <a href="{{ route('frontend.page', 'about-us') }}" class="mega-menu-item">
                                        <div class="icon"><i class="bi bi-buildings"></i></div>
                                        <div class="title">About Us</div>
                                        <div class="desc">Learn about our foundation</div>
                                    </a>
                                    <a href="{{ route('frontend.page', 'mission-vision') }}" class="mega-menu-item">
                                        <div class="icon"><i class="bi bi-bullseye"></i></div>
                                        <div class="title">Mission & Vision</div>
                                        <div class="desc">Our goals and values</div>
                                    </a>
                                    <a href="{{ route('frontend.page', 'history') }}" class="mega-menu-item">
                                        <div class="icon"><i class="bi bi-clock-history"></i></div>
                                        <div class="title">Our History</div>
                                        <div class="desc">Journey of impact</div>
                                    </a>
                                    <a href="{{ route('frontend.page', 'chairman-message') }}" class="mega-menu-item">
                                        <div class="icon"><i class="bi bi-award"></i></div>
                                        <div class="title">Chairman Message</div>
                                        <div class="desc">Leadership vision</div>
                                    </a>
                                    <a href="{{ route('public.committee.index') }}" class="mega-menu-item">
                                        <div class="icon"><i class="bi bi-people"></i></div>
                                        <div class="title">Executive Committee</div>
                                        <div class="desc">Meet our team</div>
                                    </a>
                                    <a href="{{ route('frontend.page', 'contact') }}" class="mega-menu-item">
                                        <div class="icon"><i class="bi bi-geo-alt"></i></div>
                                        <div class="title">Contact Us</div>
                                        <div class="desc">Get in touch</div>
                                    </a>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.events.index') ? 'active' : '' }}" href="{{ route('public.events.index') }}">Events</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.notices.index') ? 'active' : '' }}" href="{{ route('public.notices.index') }}">Notices</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.activities.index') ? 'active' : '' }}" href="{{ route('public.activities.index') }}">Activities</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.gallery.index') ? 'active' : '' }}" href="{{ route('public.gallery.index') }}">Gallery</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('frontend.page', 'contact') }}">Contact</a>
                        </li>
                    </ul>

                    <div class="header-actions">
                        <button class="btn-search" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="bi bi-search"></i></button>
                        <a href="{{ route('donate') }}" class="btn-donate">
                            <i class="bi bi-heart-fill"></i> Donate Now
                        </a>
                        <a href="#" class="btn-join">Join Member</a>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px;">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="text-white fw-bold mb-0">Search</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" placeholder="Search for members, events, notices..." style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 15px 0 0 15px;">
                            <button class="btn btn-primary px-4" type="submit" style="border-radius: 0 15px 15px 0;">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                    <div class="mt-4">
                        <p class="text-white-50 small mb-3">Popular Searches:</p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('public.events.index') }}" class="badge bg-secondary text-decoration-none px-3 py-2 rounded-pill">Events</a>
                            <a href="{{ route('public.members.index') ?? '#' }}" class="badge bg-secondary text-decoration-none px-3 py-2 rounded-pill">Members</a>
                            <a href="{{ route('public.notices.index') }}" class="badge bg-secondary text-decoration-none px-3 py-2 rounded-pill">Notices</a>
                            <a href="{{ route('donate') }}" class="badge bg-secondary text-decoration-none px-3 py-2 rounded-pill">Donate</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out',
            once: true,
            offset: 100
        });

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.main-header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Mobile menu handling
        document.querySelectorAll('.mega-menu-trigger > .nav-link').forEach(function(trigger) {
            trigger.addEventListener('click', function(e) {
                if (window.innerWidth < 992) {
                    e.preventDefault();
                    this.parentElement.classList.toggle('show');
                }
            });
        });
    </script>
</body>
</html>
