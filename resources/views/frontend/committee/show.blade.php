@extends('frontend.layouts.premium')

@section('content')
<!-- Page Header -->
<section class="py-5 text-center position-relative" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
    <div class="container py-5">
        <nav aria-label="breadcrumb" data-aos="fade-up">
            <ol class="breadcrumb justify-content-center mb-3">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.committee.index') }}" class="text-white-50">Committee</a></li>
                <li class="breadcrumb-item text-white">{{ $member->name }}</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold text-white" data-aos="fade-up" data-aos-delay="100">{{ $member->name }}</h1>
        <p class="lead text-white opacity-75" data-aos="fade-up" data-aos-delay="200">{{ $member->position }}</p>
    </div>
</section>

<!-- Profile Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Profile Card -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-lg rounded-4" data-aos="fade-right">
                    <div class="card-body text-center p-5">
                        <!-- Photo -->
                        <div class="mb-4">
                            @if($member->photo)
                                <img src="{{ asset('storage/members/' . $member->photo) }}" 
                                     alt="{{ $member->name }}" 
                                     class="rounded-circle border border-4 border-primary"
                                     style="width: 180px; height: 180px; object-fit: cover;">
                            @else
                                <div class="rounded-circle border border-4 border-primary d-flex align-items-center justify-content-center mx-auto"
                                     style="width: 180px; height: 180px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
                                    <span class="text-white display-3 fw-bold">{{ substr($member->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>

                        <h4 class="fw-bold mb-1">{{ $member->name }}</h4>
                        <p class="text-primary fw-semibold mb-3">{{ $member->position }}</p>
                        
                        <span class="badge bg-danger mb-3">
                            <i class="bi bi-droplet-fill me-1"></i>{{ $member->blood_group ?? 'N/A' }}
                        </span>

                        <hr class="my-4">

                        <!-- Quick Info -->
                        <div class="text-start">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3 text-primary">
                                    <i class="bi bi-calendar3 fs-5"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Member Since</small>
                                    <p class="mb-0 fw-medium">{{ $member->join_date ? date('M Y', strtotime($member->join_date)) : 'N/A' }}</p>
                                </div>
                            </div>
                            
                            @if($member->mobile)
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3 text-primary">
                                    <i class="bi bi-telephone fs-5"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Phone</small>
                                    <p class="mb-0 fw-medium">{{ $member->mobile }}</p>
                                </div>
                            </div>
                            @endif

                            @if($member->email)
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3 text-primary">
                                    <i class="bi bi-envelope fs-5"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Email</small>
                                    <p class="mb-0 fw-medium">{{ $member->email }}</p>
                                </div>
                            </div>
                            @endif

                            @if($member->occupation)
                            <div class="d-flex align-items-center">
                                <div class="me-3 text-primary">
                                    <i class="bi bi-briefcase fs-5"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Occupation</small>
                                    <p class="mb-0 fw-medium">{{ $member->occupation }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <hr class="my-4">

                        <a href="{{ route('public.committee.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-left me-2"></i>Back to Committee
                        </a>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="col-lg-8">
                <!-- Personal Information -->
                <div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-left">
                    <div class="card-header bg-white py-4 border-0">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-person me-2 text-primary"></i>Personal Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Member ID</label>
                                <p class="mb-0 fw-medium">{{ $member->member_id ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Father's Name</label>
                                <p class="mb-0 fw-medium">{{ $member->father_name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Mother's Name</label>
                                <p class="mb-0 fw-medium">{{ $member->mother_name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Date of Birth</label>
                                <p class="mb-0 fw-medium">{{ $member->date_of_birth ? date('d M Y', strtotime($member->date_of_birth)) : 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Gender</label>
                                <p class="mb-0 fw-medium">{{ ucfirst($member->gender ?? 'N/A') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Blood Group</label>
                                <p class="mb-0 fw-medium">{{ $member->blood_group ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="card border-0 shadow-sm rounded-4" data-aos="fade-left" data-aos-delay="100">
                    <div class="card-header bg-white py-4 border-0">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>Address Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Present Address</label>
                                <p class="mb-0 fw-medium">{{ $member->present_address ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Permanent Address</label>
                                <p class="mb-0 fw-medium">{{ $member->permanent_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
