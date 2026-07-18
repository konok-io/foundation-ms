@extends('frontend.layouts.app')

@section('content')
<!-- Page Header -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-2">{{ $locale === 'bn' && $page->title_bn ? $page->title_bn : $page->title }}</h1>
                @if($page->excerpt)
                <p class="lead mb-0">
                    {{ $locale === 'bn' && $page->excerpt_bn ? $page->excerpt_bn : $page->excerpt }}
                </p>
                @endif
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item text-white">{{ $locale === 'bn' && $page->title_bn ? $page->title_bn : $page->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Page Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @if($page->image)
                <div class="mb-4 text-center">
                    <img src="{{ asset('storage/' . $page->image) }}" alt="{{ $page->title }}" class="img-fluid rounded shadow-sm" style="max-height: 400px;">
                </div>
                @endif
                
                <div class="cms-content">
                    {!! $locale === 'bn' && $page->content_bn ? $page->content_bn : $page->content !!}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Pages -->
@if($relatedPages->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="fw-bold mb-4">Related Pages</h3>
        <div class="row g-4">
            @foreach($relatedPages as $related)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    @if($related->image)
                    <img src="{{ asset('storage/' . $related->image) }}" class="card-img-top" alt="{{ $related->title }}" style="height: 150px; object-fit: cover;">
                    @else
                    <div class="card-img-top bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 150px;">
                        <i class="{{ $related->icon ?? 'bi bi-file-text' }} text-primary" style="font-size: 3rem;"></i>
                    </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $related->title }}</h5>
                        @if($related->excerpt)
                        <p class="card-text text-muted small">{{ Str::limit($related->excerpt, 100) }}</p>
                        @endif
                        <a href="{{ route('frontend.page', $related->slug) }}" class="btn btn-outline-primary btn-sm">Read More</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('styles')
<style>
.cms-content {
    line-height: 1.8;
    font-size: 1.05rem;
}
.cms-content h2 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #333;
}
.cms-content h3 {
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: #444;
}
.cms-content p {
    margin-bottom: 1rem;
}
.cms-content ul, .cms-content ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}
.cms-content li {
    margin-bottom: 0.5rem;
}
.cms-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}
.cms-content blockquote {
    border-left: 4px solid var(--primary-color, #4F46E5);
    padding-left: 1rem;
    margin: 1rem 0;
    font-style: italic;
    color: #666;
}
</style>
@endpush
