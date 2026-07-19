<div class="admin-topbar">
    <div class="topbar-left">
        <button class="toggle-btn" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <button class="toggle-btn d-lg-none" id="mobileSidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <div>
            @if(isset($page_title))
            <h4 class="page-title">{{ $page_title }}</h4>
            <nav class="breadcrumb-nav">
                <a href="{{ route('admin.dashboard') }}">Home</a>
                <i class="bi bi-chevron-right"></i>
                <span>{{ $page_title }}</span>
            </nav>
            @else
            <h4 class="page-title">Dashboard</h4>
            @endif
        </div>
    </div>
    
    <div class="topbar-right">
        <a href="{{ route('admin.notifications.index') }}" class="topbar-btn" title="Notifications">
            <i class="bi bi-bell"></i>
            @php
                $unreadNotifications = Auth::user() ? Auth::user()->unreadNotifications()->count() : 0;
            @endphp
            @if($unreadNotifications > 0)
            <span class="badge">{{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}</span>
            @endif
        </a>
        <a href="{{ route('admin.notifications.index') }}" class="topbar-btn" title="Messages">
            <i class="bi bi-chat-dots"></i>
        </a>
        <a href="{{ route('admin.profile') }}" class="topbar-profile">
            <img src="{{ Auth::user()->avatar_url ?? asset('images/avatar.png') }}" alt="Profile">
            <div class="topbar-profile-info">
                <h6>{{ Auth::user()->name }}</h6>
                <small>{{ Auth::user()->roles->first()->name ?? 'Admin' }}</small>
            </div>
        </a>
    </div>
</div>
