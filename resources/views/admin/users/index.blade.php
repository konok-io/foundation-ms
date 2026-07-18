@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item"><a href="#">Users</a></li>')

@can('users.create')
@section('page_actions')
<a href="{{ route('admin.users.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-2"></i>Add User
</a>
@endsection
@endcan

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Users List</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex gap-2">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="usersTable">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Roles</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle" width="40" height="40">
                        </td>
                        <td>
                            <strong>{{ $user->name }}</strong>
                            @if($user->username)
                            <br><small class="text-muted">{{ '@' . $user->username }}</small>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            @foreach($user->roles as $role)
                            <span class="badge bg-{{ $role->name == 'Super Admin' ? 'danger' : ($role->name == 'Admin' ? 'primary' : 'secondary') }}">
                                {{ $role->name }}
                            </span>
                            @endforeach
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'inactive' ? 'warning' : 'danger') }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>
                            @if($user->last_login)
                            <small>{{ $user->last_login->diffForHumans() }}</small>
                            @else
                            <span class="text-muted">Never</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @can('users.view')
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endcan
                                @can('users.edit')
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-success" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('users.delete')
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger delete-btn" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="bi bi-people" style="font-size: 3rem;"></i>
                            <p class="mt-2">No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            "paging": false,
            "searching": false,
            "ordering": true,
            "info": false
        });
    });
</script>
@endpush
