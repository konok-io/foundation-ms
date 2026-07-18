@extends('layouts.frontend')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2><i class="bi bi-{{ $type === 'photo' ? 'image' : 'play-circle' }} me-2"></i>{{ $type === 'photo' ? 'Photo Gallery' : 'Video Gallery' }}</h2>
            <p class="text-muted">Browse our {{ $type === 'photo' ? 'photos' : 'videos' }}</p>
        </div>

        <div class="text-center mb-4">
            <div class="btn-group" role="group">
                <a href="{{ route('public.gallery.index', ['type' => 'photo']) }}" class="btn btn-{{ $type !== 'video' ? 'primary' : 'outline-primary' }}">
                    <i class="bi bi-image me-2"></i>Photos
                </a>
                <a href="{{ route('public.gallery.index', ['type' => 'video']) }}" class="btn btn-{{ $type === 'video' ? 'danger' : 'outline-danger' }}">
                    <i class="bi bi-play-circle me-2"></i>Videos
                </a>
            </div>
        </div>

        @if($albums->count() > 0)
        <div class="row">
            @foreach($albums as $album)
            <div class="col-md-4 col-lg-3 mb-4">
                <a href="{{ route('public.gallery.show', $album) }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm">
                        @if($album->cover_image)
                        <img src="{{ asset('storage/' . $album->cover_image) }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $album->title }}">
                        @else
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-gradient text-white" style="height: 180px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="bi bi-images" style="font-size: 3rem;"></i>
                        </div>
                        @endif
                        <div class="card-body text-center">
                            <h6 class="card-title text-dark">{{ $album->title }}</h6>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-images me-1"></i>{{ $album->images_count }} items
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $albums->withQueryString()->links() }}
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-2"></i>
            No {{ $type === 'photo' ? 'photos' : 'videos' }} available at the moment.
        </div>
        @endif
    </div>
</section>
@endsection
