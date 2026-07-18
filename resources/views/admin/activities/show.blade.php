@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.activities.index') }}">Activities</a></li>
<li class="breadcrumb-item active">{{ $activity->title }}</li>

@section('page_actions')
<div class="btn-group">
    @can('activities.edit')
    <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-2"></i>Edit
    </a>
    @endcan
    <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

@if($activity->image)
<div class="mb-4">
    <img src="{{ asset('storage/' . $activity->image) }}" class="img-fluid rounded" alt="{{ $activity->title }}" style="max-height: 300px;">
</div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $activity->title }}</h5>
                    <span class="badge bg-{{ $activity->status_class }}">{{ $activity->status_text }}</span>
                </div>
            </div>
            <div class="card-body">
                <span class="badge bg-primary mb-3">{{ $activity->type }}</span>
                
                @if($activity->description)
                <p>{{ $activity->description }}</p>
                @endif
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th class="bg-light">Start Date</th>
                                <td>{{ $activity->start_date->format('d M Y') }}</td>
                            </tr>
                            @if($activity->end_date)
                            <tr>
                                <th class="bg-light">End Date</th>
                                <td>{{ $activity->end_date->format('d M Y') }}</td>
                            </tr>
                            @endif
                            @if($activity->location)
                            <tr>
                                <th class="bg-light">Location</th>
                                <td>{{ $activity->location }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th class="bg-light">Beneficiaries</th>
                                <td>{{ $activity->beneficiaries_count }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Volunteers</th>
                                <td>{{ $activity->volunteers_count }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Budget</th>
                                <td>{{ number_format($activity->budget, 2) }} SAR</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Update Status</label>
                    <form action="{{ route('admin.activities.status', $activity) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <select name="status" class="form-select">
                                @foreach(\App\Models\Activity::STATUSES as $key => $status)
                                <option value="{{ $key }}" {{ $activity->status == $key ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
                
                @can('activities.delete')
                <hr>
                <form action="{{ route('admin.activities.destroy', $activity) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100 delete-btn">
                        <i class="bi bi-trash me-2"></i>Delete Activity
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
