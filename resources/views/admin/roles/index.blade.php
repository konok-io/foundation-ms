@extends('admin.layouts.app')

@section('content')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
@endsection

@can('roles.create')
@section('page_actions')
<a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-2"></i>Create Role
</a>
@endsection
@endcan

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Roles List</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.roles.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control" placeholder="Search roles..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary ms-2">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Role Name</th>
                        <th>Description</th>
                        <th>Permissions</th>
                        <th>Users</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $role->name }}</strong>
                        </td>
                        <td>{{ $role->description ?? '-' }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $role->permissions->count() }}</span>
                        </td>
                        <td>
                            @php
                                $userCount = \App\Models\User::role($role->name)->count();
                            @endphp
                            <span class="badge bg-{{ $userCount > 0 ? 'info' : 'secondary' }}">{{ $userCount }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @can('roles.view')
                                <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endcan
                                @can('roles.edit')
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-success" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('roles.delete')
                                @if($role->name !== 'Super Admin')
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger delete-btn" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-shield" style="font-size: 3rem;"></i>
                            <p class="mt-2">No roles found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $roles->withQueryString()->links() }}
    </div>
</div>
@endsection
