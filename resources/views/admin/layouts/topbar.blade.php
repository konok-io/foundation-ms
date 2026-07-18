<header class="topbar">
    <div class="d-flex align-items-center">
        <button class="btn btn-link text-dark d-lg-none me-3" id="sidebarToggle">
            <i class="bi bi-list" style="font-size: 1.5rem;"></i>
        </button>
        
        <div class="search-box me-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search...">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
        
        <div class="ms-auto d-flex align-items-center">
            <!-- Language Switcher -->
            <div class="dropdown me-3">
                <button class="btn btn-link text-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-globe"></i>
                    {{ strtoupper(app()->getLocale()) }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('language.switch', 'en') }}">English</a></li>
                    <li><a class="dropdown-item" href="{{ route('language.switch', 'bn') }}">বাংলা</a></li>
                </ul>
            </div>
            
            <!-- Notifications -->
            <div class="dropdown me-3">
                <button class="btn btn-link text-dark position-relative" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-bell" style="font-size: 1.2rem;"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">3</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end notifications-dropdown" style="width: 320px;">
                    <li class="dropdown-header">
                        <div class="d-flex justify-content-between">
                            <span>Notifications</span>
                            <a href="#" class="text-primary">Mark all read</a>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2">
                                    <div class="bg-primary text-white rounded-circle p-2">
                                        <i class="bi bi-person-plus"></i>
                                    </div>
                                </div>
                                <div>
                                    <small class="text-muted">5 mins ago</small>
                                    <p class="mb-0">New member registration</p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2">
                                    <div class="bg-success text-white rounded-circle p-2">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                </div>
                                <div>
                                    <small class="text-muted">1 hour ago</small>
                                    <p class="mb-0">Payment received</p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="text-center"><a href="#" class="text-primary">View all notifications</a></li>
                </ul>
            </div>
            
            <!-- User Dropdown -->
            <div class="dropdown user-dropdown">
                <button class="btn btn-link text-dark dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="avatar me-2">
                    <div class="d-none d-md-block">
                        <strong>{{ auth()->user()->name }}</strong>
                        <br>
                        <small class="text-muted">{{ auth()->user()->roles->first()?->name ?? 'User' }}</small>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                            <i class="bi bi-person me-2"></i>My Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.change-password') }}">
                            <i class="bi bi-key me-2"></i>Change Password
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
