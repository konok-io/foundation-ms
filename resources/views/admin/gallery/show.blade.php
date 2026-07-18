@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.gallery.index') }}">Gallery</a></li>
<li class="breadcrumb-item active">{{ $album->title }}</li>

@section('page_actions')
<div class="btn-group">
    @can('gallery.manage')
    <a href="{{ route('admin.gallery.edit', $album) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-2"></i>Edit
    </a>
    @endcan
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ $album->title }}</h5>
                @if($album->description)
                <p class="text-muted mb-0 mt-2">{{ $album->description }}</p>
                @endif
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6>Upload {{ $album->album_type === 'photo' ? 'Photos' : 'Images' }}</h6>
                    @can('gallery.manage')
                    @if($album->album_type === 'photo')
                    <form action="{{ route('admin.gallery.upload', $album) }}" method="POST" enctype="multipart/form-data" class="mb-3">
                        @csrf
                        <div class="input-group">
                            <input type="file" class="form-control" name="images[]" multiple accept="image/*" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-2"></i>Upload
                            </button>
                        </div>
                    </form>
                    @else
                    <form action="{{ route('admin.gallery.add-video', $album) }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="title" placeholder="Video Title" required>
                        </div>
                        <div class="col-md-6">
                            <input type="url" class="form-control" name="video_url" placeholder="YouTube URL" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-lg me-2"></i>Add
                            </button>
                        </div>
                    </form>
                    @endif
                    @endcan
                </div>

                @if($album->images->count() > 0)
                <div class="row">
                    @foreach($album->images as $image)
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            @if($album->album_type === 'photo')
                            <img src="{{ $image->url }}" class="card-img-top" style="height: 120px; object-fit: cover;" alt="{{ $image->title }}">
                            @else
                            <div class="position-relative">
                                <img src="https://img.youtube.com/vi/{{ $image->extractYouTubeId($image->video_url) }}/mqdefault.jpg" class="card-img-top" style="height: 120px; object-fit: cover;" alt="{{ $image->title }}">
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <i class="bi bi-play-circle-fill text-white" style="font-size: 2rem; opacity: 0.8;"></i>
                                </div>
                            </div>
                            @endif
                            <div class="card-body p-2 text-center">
                                <small class="text-muted">{{ Str::limit($image->title, 20) }}</small>
                                <div class="btn-group btn-group-sm mt-1">
                                    @can('gallery.manage')
                                    <a href="{{ route('admin.gallery.toggle-featured', $image) }}" class="btn btn-outline-{{ $image->is_featured ? 'warning' : 'secondary' }}">
                                        <i class="bi bi-star{{ $image->is_featured ? '-fill' : '' }}"></i>
                                    </a>
                                    <a href="{{ $album->album_type === 'photo' ? $image->url : $image->video_url }}" target="_blank" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.gallery.delete-image', $image) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger delete-btn">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-images" style="font-size: 3rem;"></i>
                    <p class="mt-2">No images uploaded yet</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Album Info</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-light">Type</th>
                        <td>
                            <span class="badge bg-{{ $album->album_type === 'photo' ? 'primary' : 'danger' }}">
                                {{ $album->album_type === 'photo' ? 'Photo' : 'Video' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">Images</th>
                        <td>{{ $album->images->count() }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Status</th>
                        <td>
                            @if($album->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">Created</th>
                        <td>{{ $album->created_at->format('d M Y') }}</td>
                    </tr>
                </table>
                
                @can('gallery.manage')
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.gallery.toggle', $album) }}" class="btn btn-outline-{{ $album->is_active ? 'warning' : 'success' }}">
                        <i class="bi bi-power me-2"></i>
                        {{ $album->is_active ? 'Deactivate' : 'Activate' }}
                    </a>
                    <form action="{{ route('admin.gallery.destroy', $album) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 delete-btn">
                            <i class="bi bi-trash me-2"></i>Delete Album
                        </button>
                    </form>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
