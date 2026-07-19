@extends('admin.layouts.app')

@section('title', 'Member QR Code')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $page_title ?? 'Member QR Code' }}</h5>
                </div>
                <div class="card-body text-center">
                    <div class="qr-container p-4 bg-light rounded mb-4">
                        {!! $qrCode !!}
                    </div>
                    
                    <h4 class="mb-2">{{ $member->name }}</h4>
                    <p class="text-muted mb-1">Member ID: {{ $member->member_id }}</p>
                    <p class="text-muted">Scan this QR code to verify member identity</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.members.qr-code.download', $member) }}" class="btn btn-primary">
                            <i class="bi bi-download me-2"></i> Download QR Code
                        </a>
                        <a href="{{ route('admin.members.show', $member) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Back to Member
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
