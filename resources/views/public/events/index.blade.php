@extends('frontend.layouts.premium')

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
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $event->event_type)) }}</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $event->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit(strip_tags($event->description), 100) }}</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="bi bi-calendar me-2 text-primary"></i>
                            {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                            @if($event->start_time)
                                at {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}
                            @endif
                        </li>
                        @if($event->location)
                        <li class="list-group-item">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>
                            {{ $event->location }}
                        </li>
                        @endif
                        @if($event->max_attendees)
                        <li class="list-group-item">
                            <i class="bi bi-people me-2 text-primary"></i>
                            Max {{ $event->max_attendees }} participants
                        </li>
                        @endif
                    </ul>
                    <div class="card-footer bg-white border-0">
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
