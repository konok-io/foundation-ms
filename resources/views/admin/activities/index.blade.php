@extends('admin.layouts.app')

@section('content')
@section('breadcrumb')
<li class="breadcrumb-item active">Activities</li>
@endsection

@section('page_actions')
@can('activities.create')
<a href="{{ route('admin.activities.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-2"></i>Create Activity
</a>
@endcan
@endsection

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="activity_type" class="form-select" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    @foreach(\App\Models\Activity::ACTIVITY_TYPES as $key => $type)
                    <option value="{{ $key }}" {{ request('activity_type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    @foreach(\App\Models\Activity::STATUSES as $key => $status)
                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $status }}</option>
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
        <div class="row">
            @forelse($activities as $activity)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    @if($activity->image)
                    <img src="{{ asset('storage/' . $activity->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="{{ $activity->title }}">
                    @else
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-gradient text-white" style="height: 150px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                        <i class="bi bi-activity" style="font-size: 3rem;"></i>
                    </div>
                    @endif
                    <div class="card-body">
                        <span class="badge bg-primary mb-2">{{ $activity->type }}</span>
                        <h6 class="card-title">{{ Str::limit($activity->title, 40) }}</h6>
                        <p class="text-muted small">
                            <i class="bi bi-calendar me-1"></i>{{ $activity->start_date->format('d M Y') }}
                            @if($activity->end_date && $activity->end_date != $activity->start_date)
                            - {{ $activity->end_date->format('d M Y') }}
                            @endif
                        </p>
                        <div class="d-flex justify-content-between small">
                            <span><i class="bi bi-people me-1"></i>{{ $activity->beneficiaries_count }} beneficiaries</span>
                            <span><i class="bi bi-heart me-1"></i>{{ $activity->volunteers_count }} volunteers</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-{{ $activity->status_class }}">{{ $activity->status_text }}</span>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.activities.show', $activity) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                @can('activities.edit')
                                <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('activities.delete')
                                <form action="{{ route('admin.activities.destroy', $activity) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger delete-btn"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-activity" style="font-size: 4rem;"></i>
                <h5 class="mt-3">No activities found</h5>
                @can('activities.create')
                <a href="{{ route('admin.activities.create') }}" class="btn btn-primary mt-2">Create First Activity</a>
                @endcan
            </div>
            @endforelse
        </div>
        {{ $activities->withQueryString()->links() }}
    </div>
</div>
@endsection
