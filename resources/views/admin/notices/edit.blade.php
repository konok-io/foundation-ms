@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.notices.index') }}">Notices</a></li>
<li class="breadcrumb-item active">Edit</li>

@section('page_actions')
<a href="{{ route('admin.notices.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-2"></i>Back
</a>
@endsection

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Notice</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.notices.update', $notice) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $notice->title) }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea class="form-control" name="content" rows="5">{{ old('content', $notice->content) }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Notice Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('notice_type') is-invalid @enderror" name="notice_type" required>
                                    @foreach(\App\Models\Notice::NOTICE_TYPES as $key => $type)
                                    <option value="{{ $key }}" {{ old('notice_type', $notice->notice_type) == $key ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('notice_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Priority <span class="text-danger">*</span></label>
                                <select class="form-select @error('priority') is-invalid @enderror" name="priority" required>
                                    @foreach(\App\Models\Notice::PRIORITIES as $key => $priority)
                                    <option value="{{ $key }}" {{ old('priority', $notice->priority) == $key ? 'selected' : '' }}>{{ $priority }}</option>
                                    @endforeach
                                </select>
                                @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Publish Date</label>
                                <input type="date" class="form-control" name="publish_date" value="{{ old('publish_date', $notice->publish_date?->format('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Expire Date</label>
                                <input type="date" class="form-control" name="expire_date" value="{{ old('expire_date', $notice->expire_date?->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Update Notice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
