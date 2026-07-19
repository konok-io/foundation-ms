<nav class="sidebar" id="sidebar">
    <div class="p-4">
        <div class="d-flex align-items-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="me-2" style="height: 40px;">
            <div class="sidebar-title">
                <h6 class="mb-0 text-white fw-bold">Foundation MS</h6>
                <small class="text-white-50">Admin Panel</small>
            </div>
            <button class="btn btn-link text-white ms-auto d-lg-none" id="sidebarClose">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <span class="nav-link text-white-50 text-uppercase small"><small>Management</small></span>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-check"></i>
                    <span>Roles</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.members.index') }}" class="nav-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i>
                    <span>Members</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.contributions.index') }}" class="nav-link {{ request()->routeIs('admin.contributions.*') ? 'active' : '' }}">
                    <i class="bi bi-wallet2"></i>
                    <span>Contributions</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.emergency-collections.index') }}" class="nav-link {{ request()->routeIs('admin.emergency-collections.*') ? 'active' : '' }}">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span>Emergency Collections</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i>
                    <span>Payments</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.donations.index') }}" class="nav-link {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">
                    <i class="bi bi-heart"></i>
                    <span>Donations</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.receipts.index') }}" class="nav-link {{ request()->routeIs('admin.receipts.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    <span>Receipts</span>
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <span class="nav-link text-white-50 text-uppercase small"><small>Accounting</small></span>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.incomes.index') }}" class="nav-link {{ request()->routeIs('admin.incomes.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-down-circle"></i>
                    <span>Income</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.expenses.index') }}" class="nav-link {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-up-circle"></i>
                    <span>Expense</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.ledger.index') }}" class="nav-link {{ request()->routeIs('admin.ledger.*') ? 'active' : '' }}">
                    <i class="bi bi-book"></i>
                    <span>Ledger</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.reports.daily') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    <span>Reports</span>
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <span class="nav-link text-white-50 text-uppercase small"><small>Content</small></span>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i>
                    <span>Events</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.notices.index') }}" class="nav-link {{ request()->routeIs('admin.notices.*') ? 'active' : '' }}">
                    <i class="bi bi-bell"></i>
                    <span>Notices</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.gallery.index') }}" class="nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                    <i class="bi bi-images"></i>
                    <span>Gallery</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.activities.index') }}" class="nav-link {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
                    <i class="bi bi-activity"></i>
                    <span>Activities</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.blood-donors.index') }}" class="nav-link {{ request()->routeIs('admin.blood-donors.*') ? 'active' : '' }}">
                    <i class="bi bi-droplet"></i>
                    <span>Blood Donors</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.cms.index') }}" class="nav-link {{ request()->routeIs('admin.cms.*') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i>
                    <span>CMS Pages</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.documents.index') }}" class="nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Documents</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <i class="bi bi-bell-fill"></i>
                    <span>Notifications</span>
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <span class="nav-link text-white-50 text-uppercase small"><small>System</small></span>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.audit-logs.index') }}" class="nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Audit Logs</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</nav>
