@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.members.index') }}">Members</a></li>
<li class="breadcrumb-item active">{{ $member->member_id }}</li>

@section('page_actions')
<div class="btn-group">
    @can('members.edit')
    <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-success">
        <i class="bi bi-pencil me-2"></i>Edit
    </a>
    @endcan
    @can('members.card')
    <a href="{{ route('admin.members.card', $member) }}" class="btn btn-info" target="_blank">
        <i class="bi bi-credit-card me-2"></i>ID Card
    </a>
    @endcan
    <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<div class="row">
    <div class="col-md-4">
        <!-- Profile Card -->
        <div class="card">
            <div class="card-body text-center">
                <div class="position-relative d-inline-block">
                    <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="rounded-circle mb-3" width="150" height="150" style="object-fit: cover;">
                    @if($member->blood_group)
                    <span class="position-absolute bottom-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.75rem;">
                        {{ $member->blood_group }}
                    </span>
                    @endif
                </div>
                <h4 class="mb-1">{{ $member->name }}</h4>
                @if($member->name_bn)
                <p class="text-muted mb-1">{{ $member->name_bn }}</p>
                @endif
                <p class="mb-1">
                    <span class="badge bg-primary">{{ $member->member_id }}</span>
                </p>
                <p class="mb-1">
                    <span class="badge bg-{{ $member->status ? 'success' : 'warning' }}">
                        {{ $member->status ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="badge bg-info">
                        {{ $memberTypes[$member->member_type] ?? 'General' }}
                    </span>
                </p>
                <p class="text-muted small mb-0">
                    {{ $positions[$member->position] ?? 'Member' }}
                </p>
            </div>
        </div>
        
        <!-- QR Code Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">QR Code</h6>
            </div>
            <div class="card-body text-center">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($member->member_id . '|' . $member->name . '|' . $member->mobile) }}" alt="QR Code" class="img-fluid">
                <p class="small text-muted mt-2 mb-0">Scan to verify membership</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Personal Information -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Personal Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Father's Name</td>
                                <td><strong>{{ $member->father_name }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Mother's Name</td>
                                <td><strong>{{ $member->mother_name }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Date of Birth</td>
                                <td>{{ $member->date_of_birth?->format('M d, Y') }} ({{ $member->age }} years)</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Gender</td>
                                <td>{{ ucfirst($member->gender) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Mobile</td>
                                <td><strong>{{ $member->mobile }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td>{{ $member->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">National ID</td>
                                <td>{{ $member->national_id ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Blood Group</td>
                                <td>{{ $member->blood_group ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Membership Information -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-card-text me-2"></i>Membership Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Member ID</td>
                                <td><strong>{{ $member->member_id }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Join Date</td>
                                <td>{{ $member->join_date?->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Member Type</td>
                                <td>{{ $memberTypes[$member->member_type] ?? 'General' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Position</td>
                                <td>{{ $positions[$member->position] ?? 'Member' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td>
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
        
        <!-- Address -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-house me-2"></i>Address</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted small text-uppercase">Present Address</h6>
                        <p class="mb-0">{{ $member->present_address }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted small text-uppercase">Permanent Address</h6>
                        <p class="mb-0">{{ $member->permanent_address ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Professional -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-briefcase me-2"></i>Professional Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Occupation</td>
                                <td>{{ $member->occupation ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Designation</td>
                                <td>{{ $member->designation ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Company</td>
                                <td>{{ $member->company_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Iqama Number</td>
                                <td>{{ $member->iqama_number ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Emergency Contact -->
        @if($member->emergency_contact_name)
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-telephone me-2"></i>Emergency Contact</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Name</td>
                                <td><strong>{{ $member->emergency_contact_name }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Phone</td>
                                <td>{{ $member->emergency_contact_phone }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Relation</td>
                                <td>{{ $member->emergency_contact_relation }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Nominee -->
        @if($member->nominee_name)
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person-plus me-2"></i>Nominee Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Name</td>
                                <td><strong>{{ $member->nominee_name }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Relation</td>
                                <td>{{ $member->nominee_relation }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Phone</td>
                                <td>{{ $member->nominee_phone }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
