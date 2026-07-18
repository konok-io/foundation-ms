@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item active">Notices</li>')

@section('page_actions')
@can('notices.create')
<a href="{{ route('admin.notices.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-2"></i>Create Notice
</a>
@endcan
@endsection

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="notice_type" class="form-select" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    @foreach(\App\Models\Notice::NOTICE_TYPES as $key => $type)
                    <option value="{{ $key }}" {{ request('notice_type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="priority" class="form-select" onchange="this.form.submit()">
                    <option value="">All Priority</option>
                    @foreach(\App\Models\Notice::PRIORITIES as $key => $priority)
                    <option value="{{ $key }}" {{ request('priority') == $key ? 'selected' : '' }}>{{ $priority }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Priority</th>
                        <th>Publish Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notices as $notice)
                    <tr>
                        <td>
                            <a href="{{ route('admin.notices.show', $notice) }}">
                                <strong>{{ $notice->title }}</strong>
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $notice->type }}</span>
                        </td>
                        <td>
                            @php
                                $priorityClass = match($notice->priority) {
                                    'urgent' => 'danger',
                                    'high' => 'warning',
                                    'normal' => 'info',
                                    'low' => 'secondary',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $priorityClass }}">{{ ucfirst($notice->priority) }}</span>
                        </td>
                        <td>
                            {{ $notice->publish_date?->format('d M Y') ?? 'Immediate' }}
                        </td>
                        <td>
                            @if($notice->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.notices.show', $notice) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                @can('notices.edit')
                                <a href="{{ route('admin.notices.edit', $notice) }}" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <a href="{{ route('admin.notices.toggle', $notice) }}" class="btn btn-outline-{{ $notice->is_active ? 'warning' : 'success' }}"><i class="bi bi-power"></i></a>
                                @endcan
                                @can('notices.delete')
                                <form action="{{ route('admin.notices.destroy', $notice) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger delete-btn"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No notices found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $notices->withQueryString()->links() }}
    </div>
</div>
@endsection
