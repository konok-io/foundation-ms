<nav class="sidebar" id="sidebar">
    <div class="p-4">
        <div class="d-flex align-items-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="me-2" style="height: 40px;">
            <div class="sidebar-title">
                <h6 class="mb-0 text-white fw-bold">{{ config('app.name') }}</h6>
                <small class="text-white-50">Management System</small>
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
            
            @can('users.view')
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>User Management</span>
                </a>
            </li>
            @endcan
            
            @can('roles.view')
            <li class="nav-item">
                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-check"></i>
                    <span>Role Management</span>
                </a>
            </li>
            @endcan
            
            <li class="nav-item mt-3">
                <span class="nav-link text-white-50 text-uppercase small"><small>Modules</small></span>
            </li>
            
            @can('members.view')
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-person-badge"></i>
                    <span>Members</span>
                </a>
            </li>
            @endcan
            
            @can('contributions.view')
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-wallet2"></i>
                    <span>Contributions</span>
                </a>
            </li>
            @endcan
            
            @can('emergency.view')
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span>Emergency</span>
                </a>
            </li>
            @endcan
            
            @can('donations.view')
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-heart"></i>
                    <span>Donations</span>
                </a>
            </li>
            @endcan
            
            @can('accounting.view')
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-calculator"></i>
                    <span>Accounting</span>
                </a>
            </li>
            @endcan
            
            @can('reports.view')
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    <span>Reports</span>
                </a>
            </li>
            @endcan
            
            <li class="nav-item mt-3">
                <span class="nav-link text-white-50 text-uppercase small"><small>Organization</small></span>
            </li>
            
            @can('settings.cms')
            <li class="nav-item">
                <a href="{{ route('admin.cms.index') }}" class="nav-link {{ request()->routeIs('admin.cms.*') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i>
                    <span>CMS Pages</span>
                </a>
            </li>
            @endcan
            
            @can('members.view')
            <li class="nav-item">
                <a href="{{ route('admin.members.index') }}" class="nav-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Members</span>
                </a>
            </li>
            @endcan
            
            @can('contributions.view')
            <li class="nav-item">
                <a href="{{ route('admin.contributions.index') }}" class="nav-link {{ request()->routeIs('admin.contributions.*') ? 'active' : '' }}">
                    <i class="bi bi-wallet2"></i>
                    <span>Contributions</span>
                </a>
            </li>
            @endcan
            
            @can('emergency_collections.view')
            <li class="nav-item">
                <a href="{{ route('admin.emergency-collections.index') }}" class="nav-link {{ request()->routeIs('admin.emergency-collections.*') ? 'active' : '' }}">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span>Emergency</span>
                </a>
            </li>
            @endcan
            
            @can('payments.view')
            <li class="nav-item">
                <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i>
                    <span>Payments</span>
                </a>
            </li>
            @endcan
            
            @can('receipts.view')
            <li class="nav-item">
                <a href="{{ route('admin.receipts.index') }}" class="nav-link {{ request()->routeIs('admin.receipts.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    <span>Receipts</span>
                </a>
            </li>
            @endcan
            
            @can('donations.view')
            <li class="nav-item">
                <a href="{{ route('admin.donations.index') }}" class="nav-link {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">
                    <i class="bi bi-heart"></i>
                    <span>Donations</span>
                </a>
            </li>
            @endcan
            
            <li class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#accountingMenu">
                    <i class="bi bi-calculator"></i>
                    <span>Accounting</span>
                    <i class="bi bi-chevron-right ms-auto"></i>
                </a>
                <div id="accountingMenu" class="collapse">
                    <ul class="nav flex-column ms-3">
                        @can('incomes.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.incomes.index') }}" class="nav-link {{ request()->routeIs('admin.incomes.*') ? 'active' : '' }}">
                                <i class="bi bi-arrow-down-circle"></i> Income
                            </a>
                        </li>
                        @endcan
                        @can('expenses.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.expenses.index') }}" class="nav-link {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
                                <i class="bi bi-arrow-up-circle"></i> Expense
                            </a>
                        </li>
                        @endcan
                        @can('income_categories.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.income-categories.index') }}" class="nav-link {{ request()->routeIs('admin.income-categories.*') ? 'active' : '' }}">
                                <i class="bi bi-folder"></i> Income Categories
                            </a>
                        </li>
                        @endcan
                        @can('expense_categories.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.expense-categories.index') }}" class="nav-link {{ request()->routeIs('admin.expense-categories.*') ? 'active' : '' }}">
                                <i class="bi bi-folder"></i> Expense Categories
                            </a>
                        </li>
                        @endcan
                        @can('ledger.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.ledger.index') }}" class="nav-link {{ request()->routeIs('admin.ledger.*') ? 'active' : '' }}">
                                <i class="bi bi-book"></i> Cash Book
                            </a>
                        </li>
                        @endcan
                        @can('reports.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.reports.income-statement') }}" class="nav-link {{ request()->routeIs('admin.reports.income-statement') ? 'active' : '' }}">
                                <i class="bi bi-graph-up"></i> Income Statement
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#reportsMenu">
                                <i class="bi bi-file-earmark-bar-graph"></i>
                                <span>Financial Reports</span>
                                <i class="bi bi-chevron-right ms-auto"></i>
                            </a>
                            <div id="reportsMenu" class="collapse">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.reports.daily') }}" class="nav-link {{ request()->routeIs('admin.reports.daily') ? 'active' : '' }}">
                                            <i class="bi bi-calendar-day"></i> Daily Report
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.reports.monthly') }}" class="nav-link {{ request()->routeIs('admin.reports.monthly') ? 'active' : '' }}">
                                            <i class="bi bi-calendar-month"></i> Monthly Report
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.reports.yearly') }}" class="nav-link {{ request()->routeIs('admin.reports.yearly') ? 'active' : '' }}">
                                            <i class="bi bi-calendar"></i> Yearly Report
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.reports.member-contribution') }}" class="nav-link {{ request()->routeIs('admin.reports.member-contribution') ? 'active' : '' }}">
                                            <i class="bi bi-people"></i> Member Contribution
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.reports.emergency-fund') }}" class="nav-link {{ request()->routeIs('admin.reports.emergency-fund') ? 'active' : '' }}">
                                            <i class="bi bi-exclamation-triangle"></i> Emergency Fund
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.reports.donation') }}" class="nav-link {{ request()->routeIs('admin.reports.donation') ? 'active' : '' }}">
                                            <i class="bi bi-heart"></i> Donation
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.reports.outstanding-due') }}" class="nav-link {{ request()->routeIs('admin.reports.outstanding-due') ? 'active' : '' }}">
                                            <i class="bi bi-clock-history"></i> Outstanding Due
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            
            @can('events.view')
            <li class="nav-item">
                <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i>
                    <span>Events</span>
                </a>
            </li>
            @endcan
            
            @can('blood_donors.view')
            <li class="nav-item">
                <a href="{{ route('admin.blood-donors.index') }}" class="nav-link {{ request()->routeIs('admin.blood-donors.*') ? 'active' : '' }}">
                    <i class="bi bi-droplet"></i>
                    <span>Blood Donors</span>
                </a>
            </li>
            @endcan
            
            @can('notices.view')
            <li class="nav-item">
                <a href="{{ route('admin.notices.index') }}" class="nav-link {{ request()->routeIs('admin.notices.*') ? 'active' : '' }}">
                    <i class="bi bi-bell"></i>
                    <span>Notices</span>
                </a>
            </li>
            @endcan
            
            @can('documents.view')
            <li class="nav-item">
                <a href="{{ route('admin.documents.index') }}" class="nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Documents</span>
                </a>
            </li>
            @endcan
            
            @can('gallery.manage')
            <li class="nav-item">
                <a href="{{ route('admin.gallery.index') }}" class="nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                    <i class="bi bi-images"></i>
                    <span>Gallery</span>
                </a>
            </li>
            @endcan
            
            @can('activities.view')
            <li class="nav-item">
                <a href="{{ route('admin.activities.index') }}" class="nav-link {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
                    <i class="bi bi-activity"></i>
                    <span>Activities</span>
                </a>
            </li>
            @endcan
            
            @can('notifications.view')
            <li class="nav-item">
                <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <i class="bi bi-bell"></i>
                    <span>Notifications</span>
                </a>
            </li>
            @endcan
            
            <li class="nav-item mt-3">
                <span class="nav-link text-white-50 text-uppercase small"><small>System</small></span>
            </li>
            
            @can('settings.view')
            <li class="nav-item">
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </li>
            @endcan
            
            @can('audit-logs.view')
            <li class="nav-item">
                <a href="{{ route('admin.audit-logs.index') }}" class="nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Audit Logs</span>
                </a>
            </li>
            @endcan
            
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
