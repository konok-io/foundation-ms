@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.gallery.index') }}">Gallery</a></li>
<li class="breadcrumb-item active">Create</li>

@section('page_actions')
<a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-2"></i>Back
</a>
@endsection

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Create New Album</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.gallery.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Album Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('album_type') is-invalid @enderror" name="album_type" required>
                            <option value="">Select Type</option>
                            <option value="photo" {{ old('album_type') == 'photo' ? 'selected' : '' }}>Photo Album</option>
                            <option value="video" {{ old('album_type') == 'video' ? 'selected' : '' }}>Video Album</option>
                        </select>
                        @error('album_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Create Album
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
