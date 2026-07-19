@extends('admin.layouts.app')

@section('content')
@section('breadcrumb')
<li class="breadcrumb-item active">Events</li>
@endsection

@section('page_actions')
@can('events.create')
<a href="{{ route('admin.events.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-2"></i>Create Event
</a>
@endcan
@endsection

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Events</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>Past</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="event_type" class="form-select" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    @foreach(\App\Models\Event::EVENT_TYPES as $key => $type)
                    <option value="{{ $key }}" {{ request('event_type') == $key ? 'selected' : '' }}>{{ $type }}</option>
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
                        <th>Event</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Registrations</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td>
                            <a href="{{ route('admin.events.show', $event) }}">
                                <strong>{{ $event->title }}</strong>
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $event->type }}</span>
                        </td>
                        <td>
                            {{ $event->start_date->format('d M Y') }}
                            @if($event->end_date && $event->end_date != $event->start_date)
                            - {{ $event->end_date->format('d M Y') }}
                            @endif
                        </td>
                        <td>{{ $event->location ?? '-' }}</td>
                        <td>
                            @if($event->registration_required)
                            <span class="badge bg-info">{{ $event->registrations->count() }}</span>
                            @else
                            <span class="text-muted">Open</span>
                            @endif
                        </td>
                        <td>
                            @if($event->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.events.show', $event) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                @can('events.edit')
                                <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <a href="{{ route('admin.events.toggle', $event) }}" class="btn btn-outline-{{ $event->is_active ? 'warning' : 'success' }}"><i class="bi bi-power"></i></a>
                                @endcan
                                @can('events.delete')
                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger delete-btn"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No events found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $events->withQueryString()->links() }}
    </div>
</div>
@endsection
