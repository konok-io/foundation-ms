@extends('frontend.layouts.app')

@section('content')
<div class="bg-primary text-white py-5">
    <div class="container">
        <h1 class="fw-bold">Contact Us</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                <li class="breadcrumb-item text-white">Contact</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <h2 class="fw-bold mb-4">Get In Touch</h2>
                <p class="text-muted mb-4">
                    Have questions or want to get involved? We'd love to hear from you. 
                    Reach out to us through any of the following channels.
                </p>
                
                <div class="mb-4">
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-geo-alt text-primary"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Address</h6>
                            <p class="text-muted mb-0">{{ $settings['address'] ?: '123 Foundation Street, Dhaka, Bangladesh' }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-envelope text-success"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Email</h6>
                            <p class="text-muted mb-0">{{ $settings['email'] ?: 'info@foundation.org' }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start">
                        <div class="bg-info bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-telephone text-info"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Phone</h6>
                            <p class="text-muted mb-0">{{ $settings['phone'] ?: '+880 1700-000000' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Send us a Message</h4>
                        <form>
                            <div class="mb-3">
                                <label class="form-label">Your Name</label>
                                <input type="text" class="form-control" placeholder="Enter your name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" placeholder="Enter your email">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <input type="text" class="form-control" placeholder="Enter subject">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Message</label>
                                <textarea class="form-control" rows="5" placeholder="Enter your message"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
