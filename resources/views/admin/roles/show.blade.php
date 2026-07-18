@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
<li class="breadcrumb-item active">Details</li>

@section('page_actions')
<div class="btn-group">
    @can('roles.edit')
    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-success">
        <i class="bi bi-pencil me-2"></i>Edit
    </a>
    @endcan
    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Role Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted">Name</td>
                        <td><strong>{{ $role->name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Description</td>
                        <td>{{ $role->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Guard</td>
                        <td><span class="badge bg-secondary">{{ $role->guard_name }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Created</td>
                        <td>{{ $role->created_at->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Users</td>
                        <td><span class="badge bg-info">{{ $role->users->count() }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Assigned Permissions ({{ $role->permissions->count() }})</h6>
            </div>
            <div class="card-body">
                @if($role->permissions->count() > 0)
                <div class="d-flex flex-wrap gap-2">
                    @foreach($role->permissions as $permission)
                    <span class="badge bg-primary">{{ $permission->name }}</span>
                    @endforeach
                </div>
                @else
                <p class="text-muted mb-0">No permissions assigned to this role</p>
                @endif
            </div>
        </div>
        
        @if($role->users->count() > 0)
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Users with this Role ({{ $role->users->count() }})</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($role->users as $user)
                            <tr>
                                <td>
                                    <img src="{{ $user->avatar_url }}" alt="" class="rounded-circle me-2" width="30" height="30">
                                    {{ $user->name }}
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'warning' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
