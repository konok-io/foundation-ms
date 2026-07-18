@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.activities.index') }}">Activities</a></li>
<li class="breadcrumb-item active">Edit</li>

@section('page_actions')
<a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-2"></i>Back
</a>
@endsection

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Activity</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.activities.update', $activity) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $activity->title) }}" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Activity Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('activity_type') is-invalid @enderror" name="activity_type" required>
                                    @foreach(\App\Models\Activity::ACTIVITY_TYPES as $key => $type)
                                    <option value="{{ $key }}" {{ old('activity_type', $activity->activity_type) == $key ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('activity_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3">{{ old('description', $activity->description) }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date', $activity->start_date->format('Y-m-d')) }}" required>
                                @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" name="end_date" value="{{ old('end_date', $activity->end_date?->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" value="{{ old('location', $activity->location) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    @foreach(\App\Models\Activity::STATUSES as $key => $status)
                                    <option value="{{ $key }}" {{ old('status', $activity->status) == $key ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Beneficiaries Count</label>
                                <input type="number" class="form-control" name="beneficiaries_count" value="{{ old('beneficiaries_count', $activity->beneficiaries_count) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Volunteers Count</label>
                                <input type="number" class="form-control" name="volunteers_count" value="{{ old('volunteers_count', $activity->volunteers_count) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Budget (SAR)</label>
                                <input type="number" class="form-control" name="budget" value="{{ old('budget', $activity->budget) }}" min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Cover Image</label>
                        @if($activity->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $activity->image) }}" alt="" style="height: 100px;" class="rounded">
                        </div>
                        @endif
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image</small>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Update Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
