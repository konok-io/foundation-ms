@extends('frontend.layouts.app')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2><i class="bi bi-calendar-event me-2"></i>Upcoming Events</h2>
            <p class="text-muted">Stay connected with our upcoming events and programs</p>
        </div>

        @if($events->count() > 0)
        <div class="row">
            @foreach($events as $event)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <span class="badge bg-primary mb-2">{{ $event->type }}</span>
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($event->description, 100) }}
                        </p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="bi bi-calendar me-2 text-primary"></i>
                            {{ $event->start_date->format('d M Y') }}
                        </li>
                        @if($event->location)
                        <li class="list-group-item">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>
                            {{ $event->location }}
                        </li>
                        @endif
                    </ul>
                    <div class="card-footer bg-white">
                        <a href="{{ route('public.events.show', $event) }}" class="btn btn-outline-primary w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-2"></i>
            No upcoming events at the moment. Please check back later.
        </div>
        @endif
    </div>
</section>
@endsection
