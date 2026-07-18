@extends('layouts.frontend')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2><i class="bi bi-activity me-2"></i>Our Activities</h2>
            <p class="text-muted">See the impact we're making in our community</p>
        </div>

        @if($activities->count() > 0)
        <div class="row">
            @foreach($activities as $activity)
            <div class="col-md-6 col-lg-4 mb-4">
                <a href="{{ route('public.activities.show', $activity) }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm">
                        @if($activity->image)
                        <img src="{{ asset('storage/' . $activity->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $activity->title }}">
                        @else
                        <div class="card-img-top d-flex align-items-center justify-content-center text-white" style="height: 200px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <i class="bi bi-activity" style="font-size: 4rem;"></i>
                        </div>
                        @endif
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">{{ $activity->type }}</span>
                            <h5 class="card-title text-dark">{{ Str::limit($activity->title, 50) }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($activity->description, 100) }}</p>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between text-muted small">
                                <span><i class="bi bi-calendar me-1"></i>{{ $activity->start_date->format('M Y') }}</span>
                                <span><i class="bi bi-people me-1"></i>{{ $activity->beneficiaries_count }} beneficiaries</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $activities->links() }}
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-2"></i>
            No completed activities to display yet.
        </div>
        @endif
    </div>
</section>
@endsection
