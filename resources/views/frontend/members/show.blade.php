@extends('frontend.layouts.premium')

@section('content')
<!-- Page Header -->
<section class="py-5 text-center" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
    <div class="container py-5">
        <h1 class="display-4 fw-bold text-white mb-3">Member Profile</h1>
        <p class="lead text-white opacity-75">Foundation MS Member Details</p>
    </div>
</section>

<!-- Member Details -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Profile Card -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        @if($member->photo)
                            <img src="{{ asset('storage/members/' . $member->photo) }}" alt="{{ $member->name }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
                                <span class="text-white display-4">{{ substr($member->name, 0, 1) }}</span>
                            </div>
                        @endif
                        
                        <h4 class="fw-bold mb-1">{{ $member->name }}</h4>
                        <p class="text-muted mb-2">{{ $member->member_id }}</p>
                        
                        <span class="badge bg-danger mb-3">
                            <i class="bi bi-droplet-fill me-1"></i>{{ $member->blood_group ?? 'N/A' }}
                        </span>
                        
                        @if($member->position)
                            <p class="mb-1"><strong>Position:</strong> {{ $member->position }}</p>
                        @endif
                        
                        @if($member->occupation)
                            <p class="mb-1"><strong>Occupation:</strong> {{ $member->occupation }}</p>
                        @endif
                        
                        <hr class="my-4">
                        
                        <p class="mb-1"><strong>Join Date:</strong> {{ $member->join_date ? date('d M Y', strtotime($member->join_date)) : 'N/A' }}</p>
                        <p class="mb-0"><strong>Status:</strong> 
                            <span class="badge bg-success">{{ ucfirst($member->status) }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="bi bi-person me-2"></i>Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
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
                                <label class="text-muted small">National ID</label>
                                <p class="mb-0 fw-medium">{{ $member->nid ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Mobile</label>
                                <p class="mb-0 fw-medium">{{ $member->mobile ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Email</label>
                                <p class="mb-0 fw-medium">{{ $member->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Address</h5>
                    </div>
                    <div class="card-body">
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

                <a href="{{ route('public.members.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Members
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
