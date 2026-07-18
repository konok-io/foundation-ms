@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
<li class="breadcrumb-item active">Edit</li>

@section('page_actions')
<a href="{{ route('admin.cms.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-2"></i>Back
</a>
@endsection

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit CMS Page: {{ $page->title }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.cms.update', $page) }}" method="POST" enctype="multipart/form-data" data-loading>
                    @csrf
                    @method('PUT')
                    
                    <ul class="nav nav-tabs mb-4" id="cmsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button">
                                <i class="bi bi-info-circle me-1"></i>General
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="english-tab" data-bs-toggle="tab" data-bs-target="#english" type="button">
                                <i class="bi bi-translate me-1"></i>English Content
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="bengali-tab" data-bs-toggle="tab" data-bs-target="#bengali" type="button">
                                <i class="bi bi-globe me-1"></i>Bengali Content
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button">
                                <i class="bi bi-search me-1"></i>SEO
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="cmsTabContent">
                        <!-- General Tab -->
                        <div class="tab-pane fade show active" id="general">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title (English) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title', $page->title) }}" required>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="slug" class="form-label">Slug</label>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                               id="slug" name="slug" value="{{ old('slug', $page->slug) }}">
                                        @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="page_type" class="form-label">Page Type</label>
                                        <select class="form-select @error('page_type') is-invalid @enderror" id="page_type" name="page_type">
                                            <option value="">Select Type</option>
                                            @foreach($pageTypes as $key => $type)
                                            <option value="{{ $key }}" {{ old('page_type', $page->page_type) == $key ? 'selected' : '' }}>{{ $type }}</option>
                                            @endforeach
                                        </select>
                                        @error('page_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="position" class="form-label">Position</label>
                                        <input type="number" class="form-control" id="position" name="position" value="{{ old('position', $page->position) }}" min="0">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="icon" class="form-label">Icon (Bootstrap Icons)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i id="iconPreview" class="{{ $page->icon ?? 'bi bi-info-circle' }}"></i></span>
                                            <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon', $page->icon) }}" placeholder="bi bi-heart">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Featured Image</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                        @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if($page->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $page->image) }}" alt="" class="img-thumbnail" width="100">
                                            <small class="d-block text-muted">Current image</small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $page->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">
                                        Active (Visible on website)
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- English Content Tab -->
                        <div class="tab-pane fade" id="english">
                            <div class="mb-3">
                                <label for="excerpt" class="form-label">Excerpt (Short Description)</label>
                                <textarea class="form-control" id="excerpt" name="excerpt" rows="2">{{ old('excerpt', $page->excerpt) }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control editor" id="content" name="content" rows="15">{{ old('content', $page->content) }}</textarea>
                            </div>
                        </div>
                        
                        <!-- Bengali Content Tab -->
                        <div class="tab-pane fade" id="bengali">
                            <div class="mb-3">
                                <label for="title_bn" class="form-label">Title (Bangla)</label>
                                <input type="text" class="form-control" id="title_bn" name="title_bn" value="{{ old('title_bn', $page->title_bn) }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="excerpt_bn" class="form-label">Excerpt (Short Description)</label>
                                <textarea class="form-control" id="excerpt_bn" name="excerpt_bn" rows="2">{{ old('excerpt_bn', $page->excerpt_bn) }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="content_bn" class="form-label">Content</label>
                                <textarea class="form-control editor" id="content_bn" name="content_bn" rows="15">{{ old('content_bn', $page->content_bn) }}</textarea>
                            </div>
                        </div>
                        
                        <!-- SEO Tab -->
                        <div class="tab-pane fade" id="seo">
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Update Page
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
<script>
    $(document).ready(function() {
        $('.editor').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
        
        $('#icon').on('input', function() {
            $('#iconPreview').attr('class', $(this).val() || 'bi bi-info-circle');
        });
    });
</script>
@endpush
