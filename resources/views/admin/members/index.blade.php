@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item"><a href="#">Members</a></li>')

@section('page_actions')
<div class="btn-group">
    @can('members.create')
    <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Add Member
    </a>
    @endcan
    <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
        <span class="visually-hidden">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        @can('members.export')
        <li><a class="dropdown-item" href="#" id="exportMembers"><i class="bi bi-download me-2"></i>Export</a></li>
        @endcan
        <li><a class="dropdown-item" href="#" id="printList"><i class="bi bi-printer me-2"></i>Print List</a></li>
    </ul>
</div>
@endsection

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-people text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($totalMembers) }}</h3>
                        <p class="text-muted mb-0">Total Members</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-person-check text-success" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($activeMembers) }}</h3>
                        <p class="text-muted mb-0">Active Members</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-person-dash text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($totalMembers - $activeMembers) }}</h3>
                        <p class="text-muted mb-0">Inactive Members</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">All Members</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.members.index') }}" class="d-flex gap-2">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        @foreach($statuses as $key => $status)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    <select name="member_type" class="form-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        @foreach($memberTypes as $key => $type)
                        <option value="{{ $key }}" {{ request('member_type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    <select name="blood_group" class="form-select" onchange="this.form.submit()">
                        <option value="">All Blood Groups</option>
                        @foreach($bloodGroups as $key => $group)
                        <option value="{{ $key }}" {{ request('blood_group') == $key ? 'selected' : '' }}>{{ $group }}</option>
                        @endforeach
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
            <table class="table table-hover" id="membersTable">
                <thead>
                    <tr>
                        <th width="50">Photo</th>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Blood Group</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td>
                            <img src="{{ $member->avatar_url }}" alt="" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                        </td>
                        <td>
                            <strong>{{ $member->member_id }}</strong>
                        </td>
                        <td>
                            <a href="{{ route('admin.members.show', $member) }}" class="text-decoration-none">
                                <strong>{{ $member->name }}</strong>
                            </a>
                            @if($member->occupation)
                            <br><small class="text-muted">{{ $member->occupation }}</small>
                            @endif
                        </td>
                        <td>{{ $member->mobile }}</td>
                        <td>
                            @if($member->blood_group)
                            <span class="badge bg-danger">{{ $member->blood_group }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ $memberTypes[$member->member_type] ?? 'General' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $member->status ? 'success' : 'warning' }}">
                                {{ $member->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.members.show', $member) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('members.edit')
                                <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-outline-success" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('members.card')
                                <a href="{{ route('admin.members.card', $member) }}" class="btn btn-outline-info" title="Card" target="_blank">
                                    <i class="bi bi-credit-card"></i>
                                </a>
                                @endcan
                                @can('members.delete')
                                <form action="{{ route('admin.members.destroy', $member) }}" method="POST" class="d-inline">
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
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="bi bi-people" style="font-size: 3rem;"></i>
                            <p class="mt-2">No members found</p>
                            @can('members.create')
                            <a href="{{ route('admin.members.create') }}" class="btn btn-primary btn-sm">Add First Member</a>
                            @endcan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $members->withQueryString()->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#membersTable').DataTable({
            "paging": false,
            "searching": false,
            "ordering": true,
            "info": false
        });
    });
</script>
@endpush
