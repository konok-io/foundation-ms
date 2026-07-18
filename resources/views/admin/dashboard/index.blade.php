@extends('admin.layouts.app')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Users</div>
                        <div class="h5 mb-0 fw-bold">{{ $total_users }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('admin.users.index') }}" class="text-primary text-decoration-none small">
                    View Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Active Users</div>
                        <div class="h5 mb-0 fw-bold">{{ $active_users }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person-check text-success" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('admin.users.index', ['status' => 'active']) }}" class="text-success text-decoration-none small">
                    View Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Total Members</div>
                        <div class="h5 mb-0 fw-bold">{{ $system_stats['total_members'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person-badge text-info" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="#" class="text-info text-decoration-none small">
                    View Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Total Donations</div>
                        <div class="h5 mb-0 fw-bold">{{ number_format($system_stats['total_donations'], 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-heart text-warning" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="#" class="text-warning text-decoration-none small">
                    View Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activities -->
    <div class="col-xl-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>User</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_activities as $activity)
                            <tr>
                                <td>
                                    <i class="bi bi-circle-fill text-success me-2" style="font-size: 8px;"></i>
                                    {{ $activity->description }}
                                </td>
                                <td>{{ $activity->causer?->name ?? 'System' }}</td>
                                <td class="text-muted small">{{ $activity->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No recent activities</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-xl-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @can('users.create')
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary">
                        <i class="bi bi-person-plus me-2"></i>Add New User
                    </a>
                    @endcan
                    
                    @can('roles.create')
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-outline-success">
                        <i class="bi bi-shield-plus me-2"></i>Create Role
                    </a>
                    @endcan
                    
                    @can('members.create')
                    <a href="#" class="btn btn-outline-info">
                        <i class="bi bi-person-badge me-2"></i>Add Member
                    </a>
                    @endcan
                    
                    @can('settings.view')
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-gear me-2"></i>System Settings
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        
        <!-- System Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">System Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Laravel Version</td>
                        <td class="fw-bold">{{ app()->version() }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">PHP Version</td>
                        <td class="fw-bold">{{ phpversion() }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Database</td>
                        <td class="fw-bold">{{ config('database.default') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Environment</td>
                        <td class="fw-bold">{{ config('app.env') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->hasRole('Super Admin'))
<!-- Super Admin Panel -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-gradient-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="bi bi-shield-check me-2"></i>Super Admin Dashboard
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="bi bi-database text-primary mb-2" style="font-size: 2rem;"></i>
                            <h5>{{ DB::getPdo()->query('SELECT DATABASE()')->fetchColumn() }}</h5>
                            <small class="text-muted">Current Database</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="bi bi-hdd text-success mb-2" style="font-size: 2rem;"></i>
                            <h5>{{ round(disk_free_space('/') / 1024 / 1024 / 1024, 2) }} GB</h5>
                            <small class="text-muted">Free Disk Space</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="bi bi-memory text-info mb-2" style="font-size: 2rem;"></i>
                            <h5>{{ round(memory_get_usage() / 1024 / 1024, 2) }} MB</h5>
                            <small class="text-muted">Memory Usage</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="bi bi-activity text-warning mb-2" style="font-size: 2rem;"></i>
                            <h5>{{ round(php_sapi_name() === 'cli' ? 0 : sys_getloadavg()[0], 2) }}</h5>
                            <small class="text-muted">Server Load</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
