@extends('member.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Welcome, {{ $member->name }}!</h4>
    <span class="badge bg-primary">{{ $member->member_id }}</span>
</div>

<!-- Profile Summary -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="mb-1 text-muted small">Member ID</p>
                                <p class="mb-0 fw-bold">{{ $member->member_id }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1 text-muted small">Membership Type</p>
                                <p class="mb-0 fw-bold">{{ \App\Models\Member::MEMBER_TYPES[$member->member_type] ?? 'General' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1 text-muted small">Position</p>
                                <p class="mb-0 fw-bold">{{ \App\Models\Member::POSITIONS[$member->position] ?? 'Member' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ number_format($member->contributions_count ?? 0) }}</h3>
                        <small>Total Contributions</small>
                    </div>
                    <i class="bi bi-wallet2" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ number_format($member->payments_count ?? 0) }}</h3>
                        <small>Payments Made</small>
                    </div>
                    <i class="bi bi-cash-stack" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ number_format($pendingDues ?? 0) }}</h3>
                        <small>Pending Dues</small>
                    </div>
                    <i class="bi bi-exclamation-circle" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ number_format($member->donations_count ?? 0) }}</h3>
                        <small>Donations</small>
                    </div>
                    <i class="bi bi-heart" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('member.profile') }}" class="btn btn-outline-primary">
                        <i class="bi bi-person me-2"></i>View Profile
                    </a>
                    <a href="{{ route('member.card') }}" class="btn btn-outline-info">
                        <i class="bi bi-credit-card me-2"></i>Download ID Card
                    </a>
                    <a href="{{ route('member.contributions') }}" class="btn btn-outline-success">
                        <i class="bi bi-wallet2 me-2"></i>View Contributions
                    </a>
                    <a href="{{ route('member.payments') }}" class="btn btn-outline-warning">
                        <i class="bi bi-receipt me-2"></i>Payment History
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contact Information -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-telephone me-2"></i>Contact Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted"><i class="bi bi-phone me-2"></i>Mobile</td>
                        <td class="text-end">{{ $member->mobile }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted"><i class="bi bi-envelope me-2"></i>Email</td>
                        <td class="text-end">{{ $member->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted"><i class="bi bi-geo-alt me-2"></i>Address</td>
                        <td class="text-end">{{ Str::limit($member->present_address, 30) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Membership Info -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Membership Info</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Join Date</td>
                        <td class="text-end">{{ $member->join_date?->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Blood Group</td>
                        <td class="text-end">{{ $member->blood_group ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td class="text-end">
                            <span class="badge bg-{{ $member->status ? 'success' : 'warning' }}">
                                {{ $member->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Recent Notices -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-megaphone me-2"></i>Recent Notices</h6>
            </div>
            <div class="card-body">
                @if(count($recentNotices ?? []) > 0)
                    @foreach($recentNotices as $notice)
                    <div class="border-bottom pb-2 mb-2">
                        <h6 class="mb-1">{{ $notice->title }}</h6>
                        <small class="text-muted">{{ $notice->created_at->diffForHumans() }}</small>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted text-center mb-0 py-3">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <br>No notices at the moment
                    </p>
                @endif
                <a href="{{ route('member.notices') }}" class="btn btn-sm btn-link">View All Notices</a>
            </div>
        </div>
    </div>
</div>
@endsection
