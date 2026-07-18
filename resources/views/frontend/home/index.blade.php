@extends('frontend.layouts.app')

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

<!-- Features Section -->
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

<!-- Statistics Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="display-4 fw-bold text-primary">500+</div>
                <p class="text-muted mb-0">Active Members</p>
            </div>
            <div class="col-6 col-md-3">
                <div class="display-4 fw-bold text-success">100K+</div>
                <p class="text-muted mb-0">Total Donations</p>
            </div>
            <div class="col-6 col-md-3">
                <div class="display-4 fw-bold text-info">50+</div>
                <p class="text-muted mb-0">Events Organized</p>
            </div>
            <div class="col-6 col-md-3">
                <div class="display-4 fw-bold text-warning">1000+</div>
                <p class="text-muted mb-0">Families Helped</p>
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
