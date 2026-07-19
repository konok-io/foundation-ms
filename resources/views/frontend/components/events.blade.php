<!-- Events Section -->
<section class="events-section py-5 position-relative overflow-hidden" style="background: var(--light);">
    <div class="container py-5">
        <!-- Section Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-5">
            <div data-aos="fade-right">
                <span class="px-4 py-2 rounded-pill mb-3 d-inline-block" style="background: rgba(14, 116, 144, 0.1); color: var(--primary); font-weight: 600;">
                    <i class="bi bi-calendar-event me-2"></i>Upcoming Events
                </span>
                <h2 class="display-5 fw-bold mb-2" style="color: var(--dark);">
                    Don't Miss Our <span style="color: var(--primary);">Events</span>
                </h2>
            </div>
            <div data-aos="fade-left">
                <a href="{{ route('public.events.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                    View All Events <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="row g-4">
            @forelse($upcomingEvents as $index => $event)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="event-card rounded-4 overflow-hidden h-100" style="background: white; box-shadow: 0 10px 40px rgba(0,0,0,0.08); transition: all 0.4s;">
                    <!-- Event Image/Gradient -->
                    <div class="event-image position-relative" style="height: 200px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
                        <div class="event-overlay"></div>
                        <!-- Date Badge -->
                        <div class="event-date-badge position-absolute top-0 start-0 m-3 p-3 text-center" style="background: white; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.15);">
                            <div class="fw-bold" style="color: var(--primary); font-size: 1.5rem;">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('d') }}
                            </div>
                            <div class="text-muted small text-uppercase">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('M') }}
                            </div>
                        </div>
                        <!-- Event Type Badge -->
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge px-3 py-2 rounded-pill" style="background: rgba(255,255,255,0.9); color: var(--dark);">
                                {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                            </span>
                        </div>
                        <!-- Countdown -->
                        <div class="event-countdown position-absolute bottom-0 start-0 end-0 p-3">
                            <div class="d-flex justify-content-center gap-3">
                                <div class="text-center">
                                    <div class="fw-bold text-white countdown-days">{{ \Carbon\Carbon::parse($event->start_date)->diffInDays(now()) }}</div>
                                    <div class="text-white small opacity-75">Days</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Event Content -->
                    <div class="event-content p-4">
                        <h4 class="fw-bold mb-3" style="color: var(--dark);">{{ $event->title }}</h4>
                        <p class="text-muted mb-3">{{ Str::limit(strip_tags($event->description), 100) }}</p>
                        
                        <div class="event-meta d-flex flex-wrap gap-3 mb-4">
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-clock me-2" style="color: var(--primary);"></i>
                                {{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('h:i A') : 'TBA' }}
                            </div>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-geo-alt me-2" style="color: var(--danger);"></i>
                                {{ Str::limit($event->location, 20) ?? 'TBA' }}
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            @if($event->max_attendees)
                            <div class="small text-muted">
                                <i class="bi bi-people me-1"></i> {{ $event->max_attendees }} spots
                            </div>
                            @else
                            <div></div>
                            @endif
                            <a href="{{ route('public.events.show', $event) }}" class="btn btn-sm px-4 rounded-pill" style="background: var(--gradient-primary); color: white;">
                                Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- Demo Event Cards when no events -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="event-card rounded-4 overflow-hidden h-100" style="background: white; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
                    <div class="event-image position-relative" style="height: 200px; background: linear-gradient(135deg, #0E7490 0%, #16A34A 100%);">
                        <div class="event-date-badge position-absolute top-0 start-0 m-3 p-3 text-center" style="background: white; border-radius: 12px;">
                            <div class="fw-bold" style="color: var(--primary); font-size: 1.5rem;">15</div>
                            <div class="text-muted small">AUG</div>
                        </div>
                    </div>
                    <div class="event-content p-4">
                        <h4 class="fw-bold mb-3">Annual General Meeting</h4>
                        <p class="text-muted mb-3">Join us for our annual gathering to discuss progress and future plans.</p>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('public.events.index') }}" class="btn btn-sm px-4 rounded-pill" style="background: var(--gradient-primary); color: white;">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="event-card rounded-4 overflow-hidden h-100" style="background: white; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
                    <div class="event-image position-relative" style="height: 200px; background: linear-gradient(135deg, #F59E0B 0%, #EF4444 100%);">
                        <div class="event-date-badge position-absolute top-0 start-0 m-3 p-3 text-center" style="background: white; border-radius: 12px;">
                            <div class="fw-bold" style="color: var(--accent); font-size: 1.5rem;">20</div>
                            <div class="text-muted small">AUG</div>
                        </div>
                    </div>
                    <div class="event-content p-4">
                        <h4 class="fw-bold mb-3">Blood Donation Camp</h4>
                        <p class="text-muted mb-3">Your donation can save lives. Join our blood donation drive.</p>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('public.events.index') }}" class="btn btn-sm px-4 rounded-pill" style="background: var(--gradient-primary); color: white;">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="event-card rounded-4 overflow-hidden h-100" style="background: white; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
                    <div class="event-image position-relative" style="height: 200px; background: linear-gradient(135deg, #8B5CF6 0%, #A855F7 100%);">
                        <div class="event-date-badge position-absolute top-0 start-0 m-3 p-3 text-center" style="background: white; border-radius: 12px;">
                            <div class="fw-bold" style="color: #8B5CF6; font-size: 1.5rem;">25</div>
                            <div class="text-muted small">AUG</div>
                        </div>
                    </div>
                    <div class="event-content p-4">
                        <h4 class="fw-bold mb-3">Education Workshop</h4>
                        <p class="text-muted mb-3">Skill development workshop for students and young professionals.</p>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('public.events.index') }}" class="btn btn-sm px-4 rounded-pill" style="background: var(--gradient-primary); color: white;">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<style>
.event-card {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.event-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 30px 60px rgba(0,0,0,0.15) !important;
}

.event-card:hover .event-image img {
    transform: scale(1.1);
}

.event-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 50%);
}

.countdown-days {
    font-size: 2rem;
    font-family: 'Poppins', sans-serif;
}
</style>
