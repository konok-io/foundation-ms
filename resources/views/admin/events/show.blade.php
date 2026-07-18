@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
<li class="breadcrumb-item active">{{ $event->title }}</li>

@section('page_actions')
<div class="btn-group">
    @can('events.edit')
    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-2"></i>Edit
    </a>
    @endcan
    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<!-- Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary bg-opacity-10">
            <div class="card-body text-center">
                <h3>{{ $stats['total_registrations'] }}</h3>
                <p class="mb-0">Registrations</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success bg-opacity-10">
            <div class="card-body text-center">
                <h3>{{ $stats['attended'] }}</h3>
                <p class="mb-0">Attended</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger bg-opacity-10">
            <div class="card-body text-center">
                <h3>{{ $stats['cancelled'] }}</h3>
                <p class="mb-0">Cancelled</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card {{ $event->is_active ? 'bg-success' : 'bg-secondary' }} bg-opacity-10">
            <div class="card-body text-center">
                <h3>{{ $event->is_active ? 'Active' : 'Inactive' }}</h3>
                <p class="mb-0">Status</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $event->title }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-light" style="width: 30%;">Type</th>
                        <td>{{ $event->type }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Date</th>
                        <td>
                            {{ $event->start_date->format('d M Y') }}
                            @if($event->end_date && $event->end_date != $event->start_date)
                            - {{ $event->end_date->format('d M Y') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">Time</th>
                        <td>
                            @if($event->start_time)
                            {{ date('h:i A', strtotime($event->start_time)) }}
                            @if($event->end_time)
                            - {{ date('h:i A', strtotime($event->end_time)) }}
                            @endif
                            @else
                            Not specified
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">Location</th>
                        <td>{{ $event->location ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Registration</th>
                        <td>
                            @if($event->registration_required)
                            Required
                            @if($event->registration_deadline)
                            (Deadline: {{ $event->registration_deadline->format('d M Y') }})
                            @endif
                            @else
                            Not Required
                            @endif
                        </td>
                    </tr>
                    @if($event->max_attendees)
                    <tr>
                        <th class="bg-light">Max Attendees</th>
                        <td>{{ $event->max_attendees }}</td>
                    </tr>
                    @endif
                    @if($event->description)
                    <tr>
                        <th class="bg-light">Description</th>
                        <td>{{ $event->description }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Registrations</h5>
            </div>
            <div class="card-body">
                @if($event->registrations->count() > 0)
                <div class="list-group">
                    @foreach($event->registrations as $registration)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $registration->name }}</strong>
                            <br><small class="text-muted">{{ $registration->phone ?? '-' }}</small>
                        </div>
                        @can('events.edit')
                        <form action="{{ route('admin.events.registration.update', [$event, $registration]) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach(\App\Models\EventRegistration::STATUSES as $key => $status)
                                <option value="{{ $key }}" {{ $registration->status == $key ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </form>
                        @endcan
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center mb-0">No registrations yet</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
