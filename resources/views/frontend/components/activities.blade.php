<!-- Activities Section -->
<section class="activities-section py-5 position-relative overflow-hidden" style="background: var(--light);">
    <div class="container py-5">
        <!-- Section Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-5">
            <div data-aos="fade-right">
                <span class="px-4 py-2 rounded-pill mb-3 d-inline-block" style="background: rgba(22, 163, 74, 0.1); color: var(--secondary); font-weight: 600;">
                    <i class="bi bi-activity me-2"></i>Our Work
                </span>
                <h2 class="display-5 fw-bold mb-2" style="color: var(--dark);">
                    Recent <span style="color: var(--secondary);">Activities</span>
                </h2>
            </div>
            <div data-aos="fade-left">
                <a href="{{ route('public.activities.index') }}" class="btn btn-outline-success rounded-pill px-4">
                    View All <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        <!-- Activities Grid -->
        <div class="row g-4">
            @forelse($recentActivities as $index => $activity)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="activity-card rounded-4 overflow-hidden h-100" style="background: white; box-shadow: 0 10px 40px rgba(0,0,0,0.08); transition: all 0.4s;">
                    <!-- Image -->
                    <div class="activity-image position-relative overflow-hidden" style="height: 220px;">
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, var(--secondary) 0%, #22C55E 100%);">
                            <i class="bi bi-activity text-white" style="font-size: 4rem;"></i>
                        </div>
                        <div class="activity-overlay position-absolute inset-0 d-flex align-items-center justify-content-center">
                            <a href="{{ route('public.activities.show', $activity) }}" class="btn btn-light rounded-circle p-3">
                                <i class="bi bi-play-fill" style="font-size: 1.5rem;"></i>
                            </a>
                        </div>
                        <!-- Badge -->
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge px-3 py-2 rounded-pill" style="background: rgba(255,255,255,0.9); color: var(--secondary);">
                                {{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="activity-content p-4">
                        <h4 class="fw-bold mb-3">{{ $activity->title }}</h4>
                        <p class="text-muted mb-3">{{ Str::limit(strip_tags($activity->description), 100) }}</p>
                        
                        <!-- Stats -->
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="p-2 rounded-3 text-center" style="background: rgba(14, 116, 144, 0.1);">
                                    <div class="fw-bold" style="color: var(--primary);">{{ $activity->beneficiaries_count }}</div>
                                    <small class="text-muted">Beneficiaries</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded-3 text-center" style="background: rgba(239, 68, 68, 0.1);">
                                    <div class="fw-bold" style="color: var(--danger);">{{ $activity->volunteers_count }}</div>
                                    <small class="text-muted">Volunteers</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ \Carbon\Carbon::parse($activity->start_date)->format('M d, Y') }}
                            </small>
                            <a href="{{ route('public.activities.show', $activity) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                View Project
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- Demo Activity Cards -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="activity-card rounded-4 overflow-hidden h-100" style="background: white; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
                    <div class="activity-image position-relative overflow-hidden" style="height: 220px;">
                        <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=400&h=220&fit=crop" alt="Education" class="w-100 h-100" style="object-fit: cover;">
                        <div class="activity-overlay position-absolute inset-0 d-flex align-items-center justify-content-center">
                            <a href="{{ route('public.activities.index') }}" class="btn btn-light rounded-circle p-3">
                                <i class="bi bi-play-fill" style="font-size: 1.5rem;"></i>
                            </a>
                        </div>
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge px-3 py-2 rounded-pill" style="background: rgba(255,255,255,0.9); color: var(--secondary);">Education</span>
                        </div>
                    </div>
                    <div class="activity-content p-4">
                        <h4 class="fw-bold mb-3">Free Education Program</h4>
                        <p class="text-muted mb-3">Providing free education and school supplies to underprivileged children.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-people me-1"></i> 150 Students</small>
                            <a href="{{ route('public.activities.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">View</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="activity-card rounded-4 overflow-hidden h-100" style="background: white; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
                    <div class="activity-image position-relative overflow-hidden" style="height: 220px;">
                        <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=400&h=220&fit=crop" alt="Medical" class="w-100 h-100" style="object-fit: cover;">
                        <div class="activity-overlay position-absolute inset-0 d-flex align-items-center justify-content-center">
                            <a href="{{ route('public.activities.index') }}" class="btn btn-light rounded-circle p-3">
                                <i class="bi bi-play-fill" style="font-size: 1.5rem;"></i>
                            </a>
                        </div>
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge px-3 py-2 rounded-pill" style="background: rgba(255,255,255,0.9); color: var(--danger);">Medical</span>
                        </div>
                    </div>
                    <div class="activity-content p-4">
                        <h4 class="fw-bold mb-3">Medical Camp</h4>
                        <p class="text-muted mb-3">Free medical checkups and medicine distribution in rural areas.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-people me-1"></i> 300 Patients</small>
                            <a href="{{ route('public.activities.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">View</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="activity-card rounded-4 overflow-hidden h-100" style="background: white; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
                    <div class="activity-image position-relative overflow-hidden" style="height: 220px;">
                        <img src="https://images.unsplash.com/photo-1593113598332-cd288d649433?w=400&h=220&fit=crop" alt="Food" class="w-100 h-100" style="object-fit: cover;">
                        <div class="activity-overlay position-absolute inset-0 d-flex align-items-center justify-content-center">
                            <a href="{{ route('public.activities.index') }}" class="btn btn-light rounded-circle p-3">
                                <i class="bi bi-play-fill" style="font-size: 1.5rem;"></i>
                            </a>
                        </div>
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge px-3 py-2 rounded-pill" style="background: rgba(255,255,255,0.9); color: var(--accent);">Relief</span>
                        </div>
                    </div>
                    <div class="activity-content p-4">
                        <h4 class="fw-bold mb-3">Food Distribution</h4>
                        <p class="text-muted mb-3">Distributing food packages to families affected by natural disasters.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-people me-1"></i> 500 Families</small>
                            <a href="{{ route('public.activities.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">View</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<style>
.activity-card {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.activity-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 30px 60px rgba(0,0,0,0.15) !important;
}

.activity-image img {
    transition: transform 0.5s;
}

.activity-card:hover .activity-image img {
    transform: scale(1.1);
}

.activity-overlay {
    background: rgba(0,0,0,0.3);
    opacity: 0;
    transition: opacity 0.3s;
}

.activity-card:hover .activity-overlay {
    opacity: 1;
}
</style>
