@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.members.show', $member) }}">{{ $member->name }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.documents.member', $member) }}">Documents</a></li>
<li class="breadcrumb-item active">Upload</li>

@section('page_actions')
<a href="{{ route('admin.documents.member', $member) }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-2"></i>Back
</a>
@endsection

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Upload Document for {{ $member->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.documents.store', $member) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Document Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('document_type') is-invalid @enderror" name="document_type" required>
                            <option value="">Select Type</option>
                            @foreach(\App\Models\Document::DOCUMENT_TYPES as $key => $type)
                            <option value="{{ $key }}" {{ old('document_type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('document_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required placeholder="e.g., National ID Card">
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <small class="text-muted">Max size: 10MB. Allowed: PDF, JPG, PNG, DOC, DOCX</small>
                        @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="Optional notes...">{{ old('notes') }}</textarea>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-2"></i>Upload Document
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
