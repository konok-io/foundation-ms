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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0E7490;
            --primary-dark: #0a5566;
            --secondary: #16A34A;
            --accent: #F59E0B;
            --dark: #0F172A;
            --light: #F8FAFC;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --success: #22C55E;
            --danger: #EF4444;
            --warning: #F59E0B;
            --info: #3B82F6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--gray-100); color: var(--dark); overflow-x: hidden; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }

        .page-loader { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: var(--dark); display: flex; align-items: center; justify-content: center; z-index: 9999; transition: opacity 0.5s, visibility 0.5s; }
        .page-loader.hidden { opacity: 0; visibility: hidden; }

        .admin-sidebar { position: fixed; top: 0; left: 0; width: 280px; height: 100vh; background: linear-gradient(180deg, var(--dark) 0%, #1E293B 100%); z-index: 1050; transition: all 0.3s ease; overflow-y: auto; display: flex; flex-direction: column; }
        .admin-sidebar::-webkit-scrollbar { width: 6px; }
        .admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 3px; }
        .admin-sidebar.collapsed { width: 80px; }
        .admin-sidebar.collapsed .sidebar-brand-text, .admin-sidebar.collapsed .sidebar-subtitle, .admin-sidebar.collapsed .sidebar-section-title, .admin-sidebar.collapsed .nav-link-text { display: none !important; }
        .admin-sidebar.collapsed .nav-link { justify-content: center; padding: 12px; }
        .admin-sidebar.collapsed .nav-link i { margin: 0; font-size: 1.25rem; }

        .sidebar-header { padding: 25px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2); }
        .sidebar-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .logo-icon { width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 4px 15px rgba(14, 116, 144, 0.4); }
        .sidebar-brand-text h5 { color: white; font-weight: 700; font-size: 1.1rem; margin: 0; line-height: 1.2; }
        .sidebar-subtitle { color: rgba(255,255,255,0.5); font-size: 0.75rem; margin: 0; }

        .sidebar-nav { padding: 15px 0; flex: 1; }
        .sidebar-section { margin-bottom: 5px; }
        .sidebar-section-title { padding: 10px 20px 5px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.4); }
        .nav-item { margin: 2px 10px; }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.7); text-decoration: none; border-radius: 10px; transition: all 0.3s ease; font-size: 0.9rem; font-weight: 500; }
        .nav-link i { font-size: 1.2rem; width: 24px; text-align: center; flex-shrink: 0; }
        .nav-link:hover { background: rgba(255,255,255,0.1); color: white; transform: translateX(5px); }
        .nav-link.active { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; box-shadow: 0 4px 15px rgba(14, 116, 144, 0.4); }
        .nav-link.active:hover { transform: none; }

        .sidebar-footer { padding: 15px 20px; border-top: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2); }
        .logout-link { display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.7); text-decoration: none; border-radius: 10px; transition: all 0.3s ease; font-size: 0.9rem; font-weight: 500; }
        .logout-link:hover { background: rgba(239, 68, 68, 0.2); color: #FCA5A5; }

        .main-wrapper { margin-left: 280px; min-height: 100vh; transition: margin-left 0.3s ease; }
        .admin-sidebar.collapsed ~ .main-wrapper { margin-left: 80px; }

        .admin-topbar { background: white; padding: 15px 25px; border-bottom: 1px solid var(--gray-200); position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .topbar-left { display: flex; align-items: center; gap: 15px; }
        .toggle-btn { width: 40px; height: 40px; border: none; background: var(--gray-100); border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; color: var(--gray-600); }
        .toggle-btn:hover { background: var(--primary); color: white; }
        .page-title { font-size: 1.25rem; font-weight: 600; color: var(--dark); margin: 0; }
        .breadcrumb-nav { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: var(--gray-500); }
        .breadcrumb-nav a { color: var(--gray-500); text-decoration: none; transition: color 0.3s; }
        .breadcrumb-nav a:hover { color: var(--primary); }
        .topbar-right { display: flex; align-items: center; gap: 15px; }
        .topbar-btn { width: 40px; height: 40px; border: none; background: var(--gray-100); border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; color: var(--gray-600); position: relative; }
        .topbar-btn:hover { background: var(--primary); color: white; }
        .topbar-btn .badge { position: absolute; top: -5px; right: -5px; width: 18px; height: 18px; border-radius: 50%; background: var(--danger); color: white; font-size: 0.65rem; display: flex; align-items: center; justify-content: center; }
        .topbar-profile { display: flex; align-items: center; gap: 10px; padding: 5px 15px 5px 5px; background: var(--gray-100); border-radius: 30px; text-decoration: none; transition: all 0.3s ease; }
        .topbar-profile:hover { background: var(--gray-200); }
        .topbar-profile img { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; }
        .topbar-profile-info h6 { margin: 0; font-size: 0.85rem; font-weight: 600; color: var(--dark); }
        .topbar-profile-info small { color: var(--gray-500); font-size: 0.7rem; }

        .page-content { padding: 25px; }
        .premium-card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); transition: all 0.3s ease; border: none; overflow: hidden; }
        .premium-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.1); }
        .premium-card .card-header { background: transparent; border-bottom: 1px solid var(--gray-100); padding: 20px 25px; font-family: 'Poppins', sans-serif; font-weight: 600; }
        .premium-card .card-body { padding: 25px; }

        .btn-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border: none; padding: 10px 24px; border-radius: 10px; font-weight: 600; transition: all 0.3s ease; color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(14, 116, 144, 0.4); color: white; }
        .btn-success { background: linear-gradient(135deg, var(--secondary) 0%, #15803d 100%); border: none; padding: 10px 24px; border-radius: 10px; font-weight: 600; transition: all 0.3s ease; color: white; }
        .btn-success:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(22, 163, 74, 0.4); color: white; }
        .btn-outline-primary { border: 2px solid var(--primary); color: var(--primary); padding: 8px 22px; border-radius: 10px; font-weight: 600; transition: all 0.3s ease; background: transparent; }
        .btn-outline-primary:hover { background: var(--primary); color: white; transform: translateY(-2px); }

        .table { margin-bottom: 0; }
        .table thead th { background: var(--gray-50); border-bottom: 2px solid var(--gray-200); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--gray-600); padding: 15px 20px; }
        .table tbody td { padding: 15px 20px; vertical-align: middle; border-bottom: 1px solid var(--gray-100); }
        .table tbody tr:hover { background: var(--gray-50); }

        .badge { padding: 6px 12px; border-radius: 8px; font-weight: 600; font-size: 0.75rem; }
        .badge-success { background: rgba(22, 163, 74, 0.1); color: var(--secondary); }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--accent); }
        .badge-info { background: rgba(59, 130, 246, 0.1); color: var(--info); }

        @media (max-width: 991px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .admin-sidebar.collapsed ~ .main-wrapper { margin-left: 0; }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="page-loader" id="pageLoader">
        <div class="text-center">
            <div class="spinner-border text-primary mb-3" role="status" style="width: 50px; height: 50px; border-width: 4px;"><span class="visually-hidden">Loading...</span></div>
            <h4 class="text-white">Loading...</h4>
        </div>
    </div>

    @include('admin.layouts.sidebar')
    
    <div class="main-wrapper">
        @include('admin.layouts.topbar')
        <div class="page-content">
            <div class="container-fluid p-0">
                @include('partials.alerts')
                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/loadingoverlay@2.1.7/loadingoverlay.min.js"></script>
    
    <script>
        AOS.init({ duration: 600, easing: 'ease-out', once: true, offset: 50 });
        window.addEventListener('load', function() { setTimeout(function() { document.getElementById('pageLoader').classList.add('hidden'); }, 300); });
        $('#sidebarToggle').on('click', function() { $('.admin-sidebar').toggleClass('collapsed'); });
        $('#mobileSidebarToggle').on('click', function() { $('.admin-sidebar').addClass('show'); });
        $('#sidebarClose').on('click', function() { $('.admin-sidebar').removeClass('show'); });
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        
        @if(session('success'))
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: '{{ session('success') }}', showConfirmButton: false, timer: 3000, background: '#10B981', color: '#fff' });
        @endif
        @if(session('error'))
        Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: '{{ session('error') }}', showConfirmButton: false, timer: 3000, background: '#EF4444', color: '#fff' });
        @endif
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure?', text: "You won't be able to revert this!", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!', background: '#fff', color: '#333'
            }).then((result) => { if (result.isConfirmed) { window.location.href = url; } });
        });
        $('form[data-loading]').on('submit', function() {
            const btn = $(this).find('[type="submit"]');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Processing...');
        });
    </script>
    @stack('scripts')
</body>
</html>
