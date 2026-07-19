<aside class="admin-sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <div class="logo-icon">
                <i class="bi bi-building"></i>
            </div>
            <div class="sidebar-brand-text">
                <h5>Foundation MS</h5>
                <p class="sidebar-subtitle">Admin Panel</p>
            </div>
        </a>
        <button class="btn btn-link text-white d-lg-none position-absolute top-0 end-0 mt-2 me-2" id="sidebarClose" style="text-decoration: none;">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    
    <div class="user-profile">
        <div class="user-profile-inner">
            <div class="user-avatar">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="user-info">
                <p class="user-name">{{ Auth::user()->name }}</p>
                <p class="user-role">{{ Auth::user()->roles->first()->name ?? 'Admin' }}</p>
            </div>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <div class="sidebar-section">
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i>
                    <span class="nav-link-text">Dashboard</span>
                </a>
            </div>
        </div>
        
        <div class="sidebar-section">
            <div class="sidebar-section-title">Members & Users</div>
            <div class="nav-item">
                <a href="{{ route('admin.members.index') }}" class="nav-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span class="nav-link-text">Members</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i>
                    <span class="nav-link-text">Users</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-check"></i>
                    <span class="nav-link-text">Roles & Permissions</span>
                </a>
            </div>
        </div>
        
        <div class="sidebar-section">
            <div class="sidebar-section-title">Finance</div>
            <div class="nav-item">
                <a href="{{ route('admin.contributions.index') }}" class="nav-link {{ request()->routeIs('admin.contributions.*') ? 'active' : '' }}">
                    <i class="bi bi-wallet2"></i>
                    <span class="nav-link-text">Monthly Contributions</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.emergency-collections.index') }}" class="nav-link {{ request()->routeIs('admin.emergency-collections.*') ? 'active' : '' }}">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span class="nav-link-text">Emergency Funds</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i>
                    <span class="nav-link-text">Payments</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.donations.index') }}" class="nav-link {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">
                    <i class="bi bi-heart"></i>
                    <span class="nav-link-text">Donations</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.receipts.index') }}" class="nav-link {{ request()->routeIs('admin.receipts.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    <span class="nav-link-text">Receipts</span>
                </a>
            </div>
        </div>
        
        <div class="sidebar-section">
            <div class="sidebar-section-title">Accounting</div>
            <div class="nav-item">
                <a href="{{ route('admin.incomes.index') }}" class="nav-link {{ request()->routeIs('admin.incomes.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-down-circle"></i>
                    <span class="nav-link-text">Income</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.expenses.index') }}" class="nav-link {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-up-circle"></i>
                    <span class="nav-link-text">Expense</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.ledger.index') }}" class="nav-link {{ request()->routeIs('admin.ledger.*') ? 'active' : '' }}">
                    <i class="bi bi-book"></i>
                    <span class="nav-link-text">Ledger</span>
                </a>
            </div>
        </div>
        
        <div class="sidebar-section">
            <div class="sidebar-section-title">Reports</div>
            <div class="nav-item">
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    <span class="nav-link-text">Reports</span>
                </a>
            </div>
        </div>
        
        <div class="sidebar-section">
            <div class="sidebar-section-title">Content</div>
            <div class="nav-item">
                <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i>
                    <span class="nav-link-text">Events</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.notices.index') }}" class="nav-link {{ request()->routeIs('admin.notices.*') ? 'active' : '' }}">
                    <i class="bi bi-bell"></i>
                    <span class="nav-link-text">Notices</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.gallery.index') }}" class="nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                    <i class="bi bi-images"></i>
                    <span class="nav-link-text">Gallery</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.activities.index') }}" class="nav-link {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
                    <i class="bi bi-activity"></i>
                    <span class="nav-link-text">Activities</span>
                </a>
            </div>
        </div>
        
        <div class="sidebar-section">
            <div class="sidebar-section-title">Additional</div>
            <div class="nav-item">
                <a href="{{ route('admin.blood-donors.index') }}" class="nav-link {{ request()->routeIs('admin.blood-donors.*') ? 'active' : '' }}">
                    <i class="bi bi-droplet"></i>
                    <span class="nav-link-text">Blood Donors</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.cms.index') }}" class="nav-link {{ request()->routeIs('admin.cms.*') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i>
                    <span class="nav-link-text">CMS Pages</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.documents.index') }}" class="nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span class="nav-link-text">Documents</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <i class="bi bi-bell-fill"></i>
                    <span class="nav-link-text">Notifications</span>
                </a>
            </div>
        </div>
        
        <div class="sidebar-section">
            <div class="sidebar-section-title">System</div>
            <div class="nav-item">
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span class="nav-link-text">Settings</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.audit-logs.index') }}" class="nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span class="nav-link-text">Audit Logs</span>
                </a>
            </div>
        </div>
    </nav>
    
    <div class="sidebar-footer">
        <a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i>
            <span class="nav-link-text">Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</aside>
