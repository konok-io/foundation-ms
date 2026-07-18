@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
<li class="breadcrumb-item active">Details</li>

@section('page_actions')
<div class="btn-group">
    <a href="{{ route('admin.cms.edit', $page) }}" class="btn btn-success">
        <i class="bi bi-pencil me-2"></i>Edit
    </a>
    <a href="{{ route('admin.cms.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Page Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted">Title</td>
                        <td><strong>{{ $page->title }}</strong></td>
                    </tr>
                    @if($page->title_bn)
                    <tr>
                        <td class="text-muted">Title (Bangla)</td>
                        <td>{{ $page->title_bn }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">Slug</td>
                        <td><code>{{ $page->slug }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Type</td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ \App\Models\CmsPage::PAGE_TYPES[$page->page_type] ?? 'General' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Position</td>
                        <td>{{ $page->position }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <span class="badge bg-{{ $page->status ? 'success' : 'warning' }}">
                                {{ $page->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Created</td>
                        <td>{{ $page->created_at->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Updated</td>
                        <td>{{ $page->updated_at->format('M d, Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        @if($page->image || $page->icon)
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Media</h6>
            </div>
            <div class="card-body text-center">
                @if($page->image)
                <img src="{{ asset('storage/' . $page->image) }}" alt="" class="img-fluid rounded mb-3">
                @endif
                @if($page->icon)
                <div class="display-4">
                    <i class="{{ $page->icon }} text-primary"></i>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Content (English)</h6>
            </div>
            <div class="card-body">
                @if($page->excerpt)
                <div class="alert alert-secondary">
                    <strong>Excerpt:</strong> {{ $page->excerpt }}
                </div>
                @endif
                
                @if($page->content)
                <div class="content-preview">
                    {!! $page->content !!}
                </div>
                @else
                <p class="text-muted mb-0">No content added yet.</p>
                @endif
            </div>
        </div>
        
        @if($page->title_bn || $page->content_bn)
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Content (Bangla)</h6>
            </div>
            <div class="card-body">
                @if($page->excerpt_bn)
                <div class="alert alert-secondary">
                    <strong>Excerpt:</strong> {{ $page->excerpt_bn }}
                </div>
                @endif
                
                @if($page->content_bn)
                <div class="content-preview" dir="ltr" lang="bn">
                    {!! $page->content_bn !!}
                </div>
                @endif
            </div>
        </div>
        @endif
        
        @if($page->meta_title || $page->meta_description || $page->meta_keywords)
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">SEO Information</h6>
            </div>
            <div class="card-body">
                @if($page->meta_title)
                <p><strong>Meta Title:</strong> {{ $page->meta_title }}</p>
                @endif
                @if($page->meta_description)
                <p><strong>Meta Description:</strong> {{ $page->meta_description }}</p>
                @endif
                @if($page->meta_keywords)
                <p><strong>Meta Keywords:</strong> {{ $page->meta_keywords }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.content-preview {
    line-height: 1.8;
}
.content-preview img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}
</style>
@endsection
