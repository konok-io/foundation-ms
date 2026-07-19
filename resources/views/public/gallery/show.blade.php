@extends('frontend.layouts.app')

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.gallery.index') }}">Gallery</a></li>
                <li class="breadcrumb-item active">{{ $album->title }}</li>
            </ol>
        </nav>

        <div class="text-center mb-4">
            <h2>{{ $album->title }}</h2>
            @if($album->description)
            <p class="text-muted">{{ $album->description }}</p>
            @endif
        </div>

        @if($album->images->count() > 0)
        <div class="row">
            @foreach($album->images as $image)
            <div class="col-md-4 col-lg-3 mb-4">
                @if($album->album_type === 'photo')
                <a href="{{ $image->url }}" data-lightbox="gallery" data-title="{{ $image->title ?? $album->title }}">
                    <div class="card">
                        <img src="{{ $image->url }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $image->title }}">
                        @if($image->is_featured)
                        <span class="position-absolute top-0 end-0 badge bg-warning m-2">
                            <i class="bi bi-star-fill"></i>
                        </span>
                        @endif
                    </div>
                </a>
                @else
                <div class="card">
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ $image->embed_url }}" allowfullscreen></iframe>
                    </div>
                    @if($image->is_featured)
                    <span class="position-absolute top-0 end-0 badge bg-warning m-2">
                        <i class="bi bi-star-fill"></i>
                    </span>
                    @endif
                    @if($image->title)
                    <div class="card-body">
                        <small class="text-muted">{{ $image->title }}</small>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-2"></i>
            No images in this album yet.
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/lightbox.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/lightbox.min.js"></script>
@endpush
