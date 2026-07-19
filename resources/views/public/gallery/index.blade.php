@extends('frontend.layouts.premium')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2><i class="bi bi-images me-2"></i>Photo Gallery</h2>
            <p class="text-muted">Browse our photo albums from events and activities</p>
        </div>

        @if($albums->count() > 0)
        <div class="row">
            @foreach($albums as $album)
            <div class="col-md-4 col-lg-3 mb-4">
                <a href="{{ route('public.gallery.show', $album) }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm border-0">
                        @if($album->cover_image)
                        <img src="{{ asset('storage/' . $album->cover_image) }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $album->title }}">
                        @else
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-gradient text-white" style="height: 180px; background: linear-gradient(135deg, #4F46E5 0%, #10B981 100%);">
                            <i class="bi bi-images" style="font-size: 3rem;"></i>
                        </div>
                        @endif
                        <div class="card-body text-center">
                            <h6 class="card-title fw-bold text-dark">{{ $album->title }}</h6>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-images me-1"></i>{{ $album->images->count() }} items
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
            No photo albums available at the moment.
        </div>
        @endif
    </div>
</section>
@endsection
