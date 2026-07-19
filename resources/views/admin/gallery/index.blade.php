@extends('admin.layouts.app')

@section('content')
@section('breadcrumb')
<li class="breadcrumb-item active">Gallery</li>
@endsection

@section('page_actions')
@can('gallery.manage')
<a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-2"></i>Create Album
</a>
@endcan
@endsection

<div class="card mb-4">
    <div class="card-body">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.gallery.index', ['type' => 'photo']) }}" class="btn btn-{{ request('type') != 'video' ? 'primary' : 'outline-primary' }}">
                <i class="bi bi-image me-2"></i>Photo Albums
            </a>
            <a href="{{ route('admin.gallery.index', ['type' => 'video']) }}" class="btn btn-{{ request('type') == 'video' ? 'primary' : 'outline-primary' }}">
                <i class="bi bi-play-circle me-2"></i>Video Albums
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($albums->count() > 0)
        <div class="row">
            @foreach($albums as $album)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="position-relative">
                        @if($album->cover_image)
                        <img src="{{ asset('storage/' . $album->cover_image) }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="{{ $album->title }}">
                        @else
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                            <i class="bi bi-images text-muted" style="font-size: 3rem;"></i>
                        </div>
                        @endif
                        @if(!$album->is_active)
                        <span class="position-absolute top-0 end-0 badge bg-secondary m-2">Inactive</span>
                        @endif
                    </div>
                    <div class="card-body text-center">
                        <h6 class="card-title">{{ $album->title }}</h6>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-image me-1"></i>{{ $album->images_count }} items
                        </p>
                        <span class="badge bg-{{ $album->album_type === 'photo' ? 'primary' : 'danger' }}">
                            {{ $album->album_type === 'photo' ? 'Photo' : 'Video' }}
                        </span>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="btn-group btn-group-sm w-100">
                            <a href="{{ route('admin.gallery.show', $album) }}" class="btn btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('gallery.manage')
                            <a href="{{ route('admin.gallery.edit', $album) }}" class="btn btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('admin.gallery.toggle', $album) }}" class="btn btn-outline-{{ $album->is_active ? 'warning' : 'success' }}">
                                <i class="bi bi-power"></i>
                            </a>
                            <form action="{{ route('admin.gallery.destroy', $album) }}" method="POST" class="d-inline">
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
        {{ $albums->withQueryString()->links() }}
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-images" style="font-size: 4rem;"></i>
            <h5 class="mt-3">No albums found</h5>
            <p>Create your first album to get started.</p>
            @can('gallery.manage')
            <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Create Album
            </a>
            @endcan
        </div>
        @endif
    </div>
</div>
@endsection
