@extends('frontend.layouts.premium')

@section('content')
<!-- Page Header -->
<section class="py-5 text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
    <!-- Animated Background -->
    <div class="position-absolute top-0 start-0 w-100 h-100">
        <div class="position-absolute rounded-circle opacity-25" style="width: 300px; height: 300px; background: white; top: -100px; left: -100px; animation: float 6s ease-in-out infinite;"></div>
        <div class="position-absolute rounded-circle opacity-25" style="width: 200px; height: 200px; background: white; bottom: -50px; right: 10%; animation: float 8s ease-in-out infinite;"></div>
    </div>
    
    <div class="container position-relative py-5">
        <span class="badge bg-white text-primary px-4 py-2 mb-3" data-aos="fade-up">
            <i class="bi bi-shield-check me-2"></i>Our Leadership
        </span>
        <h1 class="display-4 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="100">
            Executive Committee
        </h1>
        <p class="lead text-white opacity-75" data-aos="fade-up" data-aos-delay="200">
            Meet the dedicated team leading Foundation MS towards its mission
        </p>
    </div>
    
    <!-- Wave Divider -->
    <svg class="position-absolute bottom-0 w-100" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#F8FAFC"/>
    </svg>
</section>

<!-- Committee Section -->
<section class="py-5">
    <div class="container">
        @forelse($positions as $positionName => $members)
            <div class="mb-5">
                <!-- Position Header -->
                <div class="text-center mb-4" data-aos="fade-up">
                    <h3 class="fw-bold gradient-text mb-2">{{ $positionName }}</h3>
                    <div class="mx-auto" style="width: 80px; height: 4px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); border-radius: 2px;"></div>
                </div>

                <!-- Members Grid -->
                <div class="row g-4 justify-content-center">
                    @foreach($members as $member)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="card border-0 shadow-sm h-100 committee-card">
                            <div class="card-body text-center p-4">
                                <!-- Photo -->
                                <div class="mb-4">
                                    @if($member->photo)
                                        <img src="{{ asset('storage/members/' . $member->photo) }}" 
                                             alt="{{ $member->name }}" 
                                             class="rounded-circle border border-4 border-primary"
                                             style="width: 140px; height: 140px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle border border-4 border-primary d-flex align-items-center justify-content-center mx-auto"
                                             style="width: 140px; height: 140px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
                                            <span class="text-white display-4 fw-bold">{{ substr($member->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <h5 class="fw-bold mb-1">{{ $member->name }}</h5>
                                <p class="text-primary fw-semibold mb-2">{{ $member->position }}</p>
                                
                                @if($member->blood_group)
                                <span class="badge bg-danger mb-3">
                                    <i class="bi bi-droplet-fill me-1"></i>{{ $member->blood_group }}
                                </span>
                                @endif

                                <!-- Social Links -->
                                <div class="d-flex justify-content-center gap-3 mt-3">
                                    @if($member->email)
                                    <a href="mailto:{{ $member->email }}" class="text-muted fs-5">
                                        <i class="bi bi-envelope-fill"></i>
                                    </a>
                                    @endif
                                    @if($member->mobile)
                                    <a href="tel:{{ $member->mobile }}" class="text-muted fs-5">
                                        <i class="bi bi-telephone-fill"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Hover Overlay -->
                            <div class="card-footer bg-transparent border-0 text-center pb-4">
                                <a href="{{ route('public.committee.show', $member->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                    View Profile <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @empty
            <!-- Demo Content -->
            <div class="text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">Committee information coming soon</h3>
                <p class="text-muted">Our dedicated committee members will be listed here shortly.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Call to Action -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
    <div class="container text-center">
        <h3 class="text-white fw-bold mb-3" data-aos="fade-up">Want to Join Our Team?</h3>
        <p class="text-white opacity-75 mb-4" data-aos="fade-up" data-aos-delay="100">
            Become a member and contribute to our mission of serving the community.
        </p>
        <a href="{{ route('public.members.index') }}" class="btn btn-light btn-lg rounded-pill px-5" data-aos="fade-up" data-aos-delay="200">
            <i class="bi bi-people me-2"></i>View All Members
        </a>
    </div>
</section>
@endsection

@push('styles')
<style>
.committee-card {
    border-radius: 20px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.committee-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 60px rgba(0,0,0,0.15) !important;
}

.committee-card:hover .border-primary {
    border-color: var(--secondary) !important;
    box-shadow: 0 0 20px rgba(14, 116, 144, 0.3);
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

.gradient-text {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>
@endpush
