@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.notifications.index') }}">Notifications</a></li>
<li class="breadcrumb-item active">Send</li>

@section('page_actions')
<a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-2"></i>Back
</a>
@endsection

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Send Notification</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.notifications.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Member <span class="text-danger">*</span></label>
                        <select class="form-select select2 @error('member_id') is-invalid @enderror" name="member_id" required>
                            <option value="">Select Member</option>
                            @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->member_id }})
                            </option>
                            @endforeach
                        </select>
                        @error('member_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notification Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                            @foreach(\App\Models\MemberNotification::TYPES as $key => $type)
                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required placeholder="e.g., Payment Reminder">
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="4" required placeholder="Enter notification message...">{{ old('message') }}</textarea>
                        @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>Send Notification
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2();
});
</script>
@endpush
