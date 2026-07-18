@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.emergency-collections.index') }}">Emergency Collections</a></li>
<li class="breadcrumb-item active">Edit</li>

@section('page_actions')
<a href="{{ route('admin.emergency-collections.show', $collection) }}" class="btn btn-outline-info">
    <i class="bi bi-eye me-2"></i>View
</a>
<a href="{{ route('admin.emergency-collections.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-2"></i>Back
</a>
@endsection

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Emergency Collection</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.emergency-collections.update', $collection) }}" method="POST" data-loading>
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $collection->title) }}" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    @foreach($types as $key => $type)
                                    <option value="{{ $key }}" {{ old('type', $collection->type) == $key ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="title_bn" class="form-label">Title (Bangla)</label>
                        <input type="text" class="form-control" id="title_bn" name="title_bn" value="{{ old('title_bn', $collection->title_bn) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $collection->description) }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description_bn" class="form-label">Description (Bangla)</label>
                        <textarea class="form-control" id="description_bn" name="description_bn" rows="2">{{ old('description_bn', $collection->description_bn) }}</textarea>
                    </div>
                    
                    <hr>
                    <h6>Financial Details</h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="target_amount" class="form-label">Target Amount (SAR) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('target_amount') is-invalid @enderror" id="target_amount" name="target_amount" value="{{ old('target_amount', $collection->target_amount) }}" step="0.01" min="0" required>
                                @error('target_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="amount_per_member" class="form-label">Amount Per Member (SAR)</label>
                                <input type="number" class="form-control" id="amount_per_member" name="amount_per_member" value="{{ old('amount_per_member', $collection->amount_per_member) }}" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}" {{ old('status', $collection->status) == $key ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h6>Schedule</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $collection->start_date->format('Y-m-d')) }}" required>
                                @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $collection->end_date?->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $collection->notes) }}</textarea>
                    </div>
                    
                    @if($collection->status !== 'active')
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="assign_to_members" id="assign_to_members" value="1">
                            <label class="form-check-label" for="assign_to_members">
                                Assign to all active members when activated
                            </label>
                        </div>
                    </div>
                    @endif
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Update Collection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
