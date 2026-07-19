@extends('frontend.layouts.premium')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white text-center">
                        <h4 class="mb-0"><i class="bi bi-check-circle-fill me-2"></i>Member Verified</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="rounded-circle mb-3" width="150" height="150">
                                <h5>{{ $member->name }}</h5>
                                <p class="text-muted">{{ $member->member_id }}</p>
                                @if($member->status)
                                <span class="badge bg-success">Active Member</span>
                                @else
                                <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Name:</th>
                                        <td>{{ $member->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Member ID:</th>
                                        <td><strong>{{ $member->member_id }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Blood Group:</th>
                                        <td>{{ $member->blood_group ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>{{ $member->phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $member->email ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Member Since:</th>
                                        <td>{{ $member->join_date?->format('d M Y') ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Occupation:</th>
                                        <td>{{ $member->occupation ?? 'N/A' }}</td>
                                    </tr>
                                    @if($member->address)
                                    <tr>
                                        <th>Address:</th>
                                        <td>{{ $member->address }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        
                        <div class="alert alert-success mt-4 text-center">
                            <i class="bi bi-shield-check me-2"></i>
                            This member has been verified as a legitimate member of the foundation.
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <small class="text-muted">
                            Verified at: {{ now()->format('d M Y H:i:s') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
