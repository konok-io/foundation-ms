@extends('frontend.layouts.premium')

@section('content')
<div class="bg-primary text-white py-5">
    <div class="container">
        <h1 class="fw-bold">About Us</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                <li class="breadcrumb-item text-white">About</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="fw-bold mb-4">Our Mission</h2>
                <p class="lead text-muted">
                    We are dedicated to improving the lives of community members through comprehensive welfare programs, 
                    financial support, and community development initiatives.
                </p>
                <p class="text-muted">
                    Our foundation has been serving the community for over a decade, providing assistance to those in need 
                    and creating opportunities for growth and development.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="bg-light rounded p-5 text-center">
                    <i class="bi bi-shield-check text-primary" style="font-size: 5rem;"></i>
                    <h3 class="mt-4 fw-bold">Building a Better Tomorrow</h3>
                    <p class="text-muted">Together we can make a difference</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold text-center mb-5">Our Values</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <i class="bi bi-heart-fill text-danger" style="font-size: 3rem;"></i>
                    <h4 class="fw-bold mt-3">Compassion</h4>
                    <p class="text-muted">We approach every individual with empathy and understanding.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <i class="bi bi-shield-fill-check text-success" style="font-size: 3rem;"></i>
                    <h4 class="fw-bold mt-3">Integrity</h4>
                    <p class="text-muted">We maintain transparency and accountability in all our operations.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <i class="bi bi-people-fill text-primary" style="font-size: 3rem;"></i>
                    <h4 class="fw-bold mt-3">Community</h4>
                    <p class="text-muted">We believe in the power of collective action and unity.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
