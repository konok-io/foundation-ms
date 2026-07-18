@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
<li class="breadcrumb-item active">Details</li>

@section('page_actions')
<div class="btn-group">
    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-success">
        <i class="bi bi-pencil me-2"></i>Edit
    </a>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle mb-3" width="120" height="120">
                <h4>{{ $user->name }}</h4>
                @foreach($user->roles as $role)
                <span class="badge bg-{{ $role->name == 'Super Admin' ? 'danger' : 'primary' }}">{{ $role->name }}</span>
                @endforeach
                <hr>
                <div class="d-flex justify-content-center gap-3">
                    <div>
                        <i class="bi bi-circle-fill text-{{ $user->status == 'active' ? 'success' : 'warning' }}"></i>
                        {{ ucfirst($user->status) }}
                    </div>
                    <div>
                        <i class="bi bi-calendar"></i>
                        {{ $user->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Contact Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Phone</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Username</td>
                        <td>{{ $user->username ? '@' . $user->username : '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Login Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Last Login</td>
                        <td>{{ $user->last_login ? $user->last_login->diffForHumans() : 'Never' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Last IP</td>
                        <td>{{ $user->last_login_ip ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Assigned Permissions</h6>
            </div>
            <div class="card-body">
                @if($user->getAllPermissions()->count() > 0)
                <div class="d-flex flex-wrap gap-2">
                    @foreach($user->getAllPermissions() as $permission)
                    <span class="badge bg-secondary">{{ $permission->name }}</span>
                    @endforeach
                </div>
                @else
                <p class="text-muted mb-0">No direct permissions assigned</p>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Recent Activities</h6>
            </div>
            <div class="card-body">
                @if($activities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                            <tr>
                                <td>{{ $activity->description }}</td>
                                <td class="text-muted">{{ $activity->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted mb-0">No recent activities</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
