@extends('admin.layouts.app')

@section('title', 'Member QR Code - ' . $member->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">QR Code for {{ $member->name }}</h5>
                </div>
                <div class="card-body text-center">
                    <div class="qr-container p-4 bg-light rounded">
                        <img src="{{ route('admin.members.qr-code', $member) }}" alt="QR Code" class="img-fluid">
                    </div>
                    <p class="mt-3 text-muted">Member ID: {{ $member->member_id }}</p>
                    <div class="mt-4">
                        <a href="{{ route('admin.members.qr-code', $member) }}?download=1" class="btn btn-primary">
                            <i class="bi bi-download me-2"></i> Download QR Code
                        </a>
                        <a href="{{ route('admin.members.show', $member) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Verification</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Scan this QR code to verify member identity.</p>
                    <p><strong>Verification URL:</strong></p>
                    <code class="d-block p-2 bg-light rounded">{{ route('verify.member', $member->qr_code ?? $member->member_id) }}</code>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
