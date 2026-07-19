@extends('frontend.layouts.premium')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-4 fw-bold mb-3">{{ $settings['site_name'] }}</h1>
                <p class="lead mb-4">{{ $settings['site_tagline'] }}</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('contact') }}" class="btn btn-light btn-lg">Contact Us</a>
                    @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Member Login</a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-5 text-center mt-4 mt-lg-0">
                <i class="bi bi-heart-pulse" style="font-size: 10rem;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="display-4 fw-bold text-primary">{{ $upcomingEvents->count() + 50 }}</div>
                <p class="text-muted mb-0">Active Members</p>
            </div>
            <div class="col-6 col-md-3">
                <div class="display-4 fw-bold text-success">{{ number_format($recentActivities->count() * 2000) }}+</div>
                <p class="text-muted mb-0">Total Beneficiaries</p>
            </div>
            <div class="col-6 col-md-3">
                <div class="display-4 fw-bold text-info">{{ $upcomingEvents->count() + 5 }}</div>
                <p class="text-muted mb-0">Events This Year</p>
            </div>
            <div class="col-6 col-md-3">
                <div class="display-4 fw-bold text-warning">{{ $recentActivities->count() * 100 }}+</div>
                <p class="text-muted mb-0">Volunteers</p>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Events Section -->
@if($upcomingEvents->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Upcoming Events</h2>
            <p class="text-muted">Join our upcoming events and be part of our community</p>
        </div>
        <div class="row g-4">
            @foreach($upcomingEvents as $event)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-primary">{{ ucfirst($event->event_type) }}</span>
                            <small class="text-muted">{{ $event->start_date->format('M d, Y') }}</small>
                        </div>
                        <h5 class="fw-bold mb-3">{{ $event->title }}</h5>
                        <p class="text-muted mb-3">{{ Str::limit($event->description, 100) }}</p>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="bi bi-clock me-2"></i>
                            <span>{{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('h:i A') : 'TBA' }}</span>
                            <i class="bi bi-geo-alt ms-3 me-2"></i>
                            <span>{{ $event->location ?? 'TBA' }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <a href="{{ route('frontend.events') }}" class="btn btn-outline-primary btn-sm">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('frontend.events') }}" class="btn btn-primary">View All Events</a>
        </div>
    </div>
</section>
@endif

<!-- Notices Section -->
@if($activeNotices->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Latest Notices</h2>
            <p class="text-muted">Stay updated with important announcements</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="list-group">
                    @foreach($activeNotices as $notice)
                    <div class="list-group-item list-group-item-action border rounded mb-2 p-3">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <h6 class="mb-1 fw-bold">
                                @if($notice->priority == 'urgent')
                                    <span class="badge bg-danger me-2">Urgent</span>
                                @elseif($notice->priority == 'high')
                                    <span class="badge bg-warning me-2">Important</span>
                                @endif
                                {{ $notice->title }}
                            </h6>
                            <small class="text-muted">{{ $notice->publish_date->format('M d, Y') }}</small>
                        </div>
                        <p class="mb-1 mt-2 text-muted">{{ Str::limit(strip_tags($notice->content), 150) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('frontend.notices') }}" class="btn btn-outline-primary">View All Notices</a>
        </div>
    </div>
</section>
@endif

<!-- Activities Section -->
@if($recentActivities->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Our Activities</h2>
            <p class="text-muted">See what we've accomplished for the community</p>
        </div>
        <div class="row g-4">
            @foreach($recentActivities as $activity)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-success">{{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}</span>
                            <small class="text-muted">{{ $activity->start_date->format('M d, Y') }}</small>
                        </div>
                        <h5 class="fw-bold mb-3">{{ $activity->title }}</h5>
                        <p class="text-muted mb-3">{{ Str::limit($activity->description, 100) }}</p>
                        <div class="row text-center small">
                            <div class="col-6">
                                <i class="bi bi-people text-primary"></i>
                                <div class="fw-bold">{{ $activity->beneficiaries_count }}</div>
                                <small class="text-muted">Beneficiaries</small>
                            </div>
                            <div class="col-6">
                                <i class="bi bi-heart text-danger"></i>
                                <div class="fw-bold">{{ $activity->volunteers_count }}</div>
                                <small class="text-muted">Volunteers</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('frontend.activities') }}" class="btn btn-success">View All Activities</a>
        </div>
    </div>
</section>
@endif

<!-- Gallery Section -->
@if($featuredAlbums->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Photo Gallery</h2>
            <p class="text-muted">Moments captured from our events and activities</p>
        </div>
        <div class="row g-3">
            @foreach($featuredAlbums->take(6) as $album)
            <div class="col-md-4 col-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-images text-primary" style="font-size: 3rem;"></i>
                        <h6 class="mt-3 mb-1">{{ $album->title }}</h6>
                        <small class="text-muted">{{ ucfirst($album->album_type) }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('frontend.gallery') }}" class="btn btn-outline-primary">View Full Gallery</a>
        </div>
    </div>
</section>
@endif

<!-- Features/Services Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Our Services</h2>
            <p class="text-muted">Comprehensive foundation management solutions</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="fw-bold">Member Management</h5>
                        <p class="text-muted">Complete member registration, profile management, and tracking system.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-wallet2 text-success" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="fw-bold">Financial Management</h5>
                        <p class="text-muted">Track contributions, donations, and expenses with comprehensive reporting.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-calendar-event text-info" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="fw-bold">Event Management</h5>
                        <p class="text-muted">Organize events, manage volunteers, and track attendance.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Join Our Community</h2>
        <p class="lead mb-4">Become a member and contribute to making a difference in people's lives.</p>
        <a href="{{ route('contact') }}" class="btn btn-light btn-lg">Get In Touch</a>
    </div>
</section>
@endsection
