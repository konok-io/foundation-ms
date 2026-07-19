@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item"><a href="{{ route('admin.emergency-collections.index') }}">Emergency Collections</a></li>')

@section('page_actions')
@can('emergency_collections.create')
<a href="{{ route('admin.emergency-collections.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-2"></i>New Collection
</a>
@endcan
@endsection

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-collection text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
                        <p class="text-muted mb-0">Total Collections</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-check-circle text-success" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['active']) }}</h3>
                        <p class="text-muted mb-0">Active</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-cash-stack text-info" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['total_collected'], 2) }}</h3>
                        <p class="text-muted mb-0">Collected (SAR)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-bullseye text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['total_target'], 2) }}</h3>
                        <p class="text-muted mb-0">Target (SAR)</p>
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
                <h5 class="mb-0">All Emergency Collections</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.emergency-collections.index') }}" class="d-flex gap-2">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        @foreach($statuses as $key => $status)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        @foreach($types as $key => $type)
                        <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Target</th>
                        <th>Collected</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collections as $collection)
                    <tr>
                        <td>
                            <a href="{{ route('admin.emergency-collections.show', $collection) }}" class="text-decoration-none">
                                <strong>{{ $collection->title }}</strong>
                            </a>
                            <br>
                            <small class="text-muted">{{ $collection->start_date->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $types[$collection->type] ?? $collection->type }}</span>
                        </td>
                        <td>{{ number_format($collection->target_amount, 2) }} SAR</td>
                        <td>{{ number_format($collection->collected_amount, 2) }} SAR</td>
                        <td>
                            <div style="width: 100px;">
                                <div class="progress mb-1" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: {{ $collection->progress_percentage }}%"></div>
                                </div>
                                <small class="text-muted">{{ $collection->progress_percentage }}%</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $collection->status === 'active' ? 'success' : ($collection->status === 'closed' ? 'info' : 'secondary') }}">
                                {{ ucfirst($collection->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.emergency-collections.show', $collection) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('emergency_collections.edit')
                                <a href="{{ route('admin.emergency-collections.edit', $collection) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('emergency_collections.delete')
                                <form action="{{ route('admin.emergency-collections.destroy', $collection) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
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
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                            <p class="mt-2">No emergency collections found</p>
                            @can('emergency_collections.create')
                            <a href="{{ route('admin.emergency-collections.create') }}" class="btn btn-primary btn-sm">Create First Collection</a>
                            @endcan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $collections->withQueryString()->links() }}
    </div>
</div>
@endsection
