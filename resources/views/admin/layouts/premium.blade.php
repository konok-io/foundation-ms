<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} - {{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom Premium CSS -->
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
            --warning: #F59E0B;
            --gradient-primary: linear-gradient(135deg, #0E7490 0%, #16A34A 100%);
            --gradient-accent: linear-gradient(135deg, #F59E0B 0%, #EF4444 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: var(--dark);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: var(--dark);
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }

        .sidebar-logo .logo-icon {
            width: 45px;
            height: 45px;
            background: var(--gradient-primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            flex-shrink: 0;
        }

        .sidebar-logo .logo-text {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 20px;
            color: white;
            white-space: nowrap;
        }

        .sidebar.collapsed .logo-text {
            display: none;
        }

        /* Sidebar Menu */
        .sidebar-menu {
            padding: 20px 15px;
        }

        .menu-section {
            margin-bottom: 25px;
        }

        .menu-section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            padding: 0 15px;
            margin-bottom: 10px;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .menu-section-title {
            text-align: center;
            padding: 0;
        }

        .menu-item {
            margin-bottom: 5px;
        }

        .menu-item a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 18px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .menu-item a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--primary);
            transform: scaleY(0);
            transition: transform 0.3s;
        }

        .menu-item a:hover {
            background: rgba(255,255,255,0.05);
            color: white;
        }

        .menu-item a:hover::before {
            transform: scaleY(1);
        }

        .menu-item a.active {
            background: rgba(14, 116, 144, 0.2);
            color: white;
        }

        .menu-item a.active::before {
            transform: scaleY(1);
        }

        .menu-item a i {
            font-size: 20px;
            width: 24px;
            text-align: center;
            flex-shrink: 0;
        }

        .menu-item a .menu-text {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .menu-text {
            display: none;
        }

        .menu-item a .badge {
            background: var(--danger);
            color: white;
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 20px;
        }

        .sidebar.collapsed .badge {
            display: none;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.collapsed + .main-content {
            margin-left: 80px;
        }

        /* Top Header */
        .top-header {
            background: white;
            padding: 20px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .toggle-sidebar {
            width: 45px;
            height: 45px;
            background: var(--light);
            border: none;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .toggle-sidebar:hover {
            background: var(--primary);
            color: white;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            width: 300px;
            padding: 12px 20px 12px 45px;
            border: 1px solid #e2e8f0;
            border-radius: 50px;
            background: var(--light);
            transition: all 0.3s;
        }

        .search-box input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(14, 116, 144, 0.1);
            outline: none;
        }

        .search-box i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-icon-btn {
            width: 45px;
            height: 45px;
            background: var(--light);
            border: none;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            transition: all 0.3s;
        }

        .header-icon-btn:hover {
            background: var(--primary);
            color: white;
        }

        .header-icon-btn .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 10px;
            padding: 3px 6px;
            border-radius: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 15px;
            background: var(--light);
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .user-profile:hover {
            background: #e2e8f0;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-profile .user-info {
            text-align: left;
        }

        .user-profile .user-name {
            font-weight: 600;
            font-size: 14px;
            color: var(--dark);
        }

        .user-profile .user-role {
            font-size: 12px;
            color: #64748b;
        }

        /* Page Content */
        .page-content {
            padding: 30px;
        }

        /* Cards */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(14, 116, 144, 0.1) 0%, transparent 100%);
            border-radius: 0 0 0 100%;
        }

        .stat-card .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-card .stat-value {
            font-size: 32px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .stat-card .stat-label {
            color: #64748b;
            font-size: 14px;
        }

        .stat-card .stat-change {
            position: absolute;
            top: 25px;
            right: 25px;
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .stat-card .stat-change.up {
            background: rgba(34, 197, 94, 0.1);
            color: var(--success);
        }

        .stat-card .stat-change.down {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        /* Chart Card */
        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(0,0,0,0.05);
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .table-card .card-header {
            background: white;
            padding: 20px 25px;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-card .card-body {
            padding: 0;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .search-box input {
                width: 200px;
            }
        }

        @media (max-width: 576px) {
            .search-box {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                <div class="logo-icon">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <span class="logo-text">{{ config('app.name') }}</span>
            </a>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Main Menu</div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Management</div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.members.index') }}" class="{{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span class="menu-text">Members</span>
                        <span class="badge">New</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.contributions.index') }}" class="{{ request()->routeIs('admin.contributions.*') ? 'active' : '' }}">
                        <i class="bi bi-wallet2"></i>
                        <span class="menu-text">Contributions</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.payments.index') ?? '#' }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                        <i class="bi bi-credit-card"></i>
                        <span class="menu-text">Payments</span>
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Activities</div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.events.index') ?? '#' }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event"></i>
                        <span class="menu-text">Events</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.notices.index') ?? '#' }}" class="{{ request()->routeIs('admin.notices.*') ? 'active' : '' }}">
                        <i class="bi bi-megaphone"></i>
                        <span class="menu-text">Notices</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.activities.index') ?? '#' }}" class="{{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
                        <i class="bi bi-activity"></i>
                        <span class="menu-text">Activities</span>
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">System</div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.settings.index') ?? '#' }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i>
                        <span class="menu-text">Settings</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="menu-text">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <button class="toggle-sidebar" id="toggleSidebar">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search anything...">
                </div>
            </div>

            <div class="header-right">
                <button class="header-icon-btn">
                    <i class="bi bi-bell"></i>
                    <span class="badge">3</span>
                </button>
                
                <button class="header-icon-btn">
                    <i class="bi bi-chat-left-dots"></i>
                    <span class="badge">5</span>
                </button>

                <div class="user-profile dropdown">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}&background=0E7490&color=fff" alt="User">
                    <div class="user-info d-none d-md-block">
                        <div class="user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                        <div class="user-role">{{ Auth::user()->roles->first()->name ?? 'Administrator' }}</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="page-content">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 600,
            easing: 'ease-out',
            once: true
        });

        // Toggle Sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });

        // Mobile sidebar toggle
        if (window.innerWidth < 992) {
            document.getElementById('sidebar').classList.add('show');
        }
    </script>

    @stack('scripts')
</body>
</html>
