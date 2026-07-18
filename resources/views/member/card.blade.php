@extends('member.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">My ID Card</h4>
    <button onclick="window.print()" class="btn btn-primary">
        <i class="bi bi-printer me-2"></i>Print Card
    </button>
</div>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="member-card">
            <div class="card-header-section">
                <div class="logo-section">
                    <i class="bi bi-shield-check"></i>
                    <div>
                        <strong>{{ config('app.name') }}</strong>
                        <small>Foundation Management System</small>
                    </div>
                </div>
                <div class="member-badge">{{ $member->member_id }}</div>
            </div>
            
            <div class="card-body-section">
                <div class="photo-section">
                    <img src="{{ $member->photo ? asset('storage/members/' . $member->photo) : asset('images/avatar.png') }}" alt="{{ $member->name }}">
                </div>
                <div class="info-section">
                    <h5 class="name">{{ $member->name }}</h5>
                    <p class="designation">{{ \App\Models\Member::MEMBER_TYPES[$member->member_type] ?? 'Member' }}</p>
                    <div class="details">
                        @if($member->occupation)
                        <div><i class="bi bi-briefcase"></i> {{ $member->occupation }}</div>
                        @endif
                        <div><i class="bi bi-telephone"></i> {{ $member->mobile }}</div>
                        @if($member->blood_group)
                        <div><i class="bi bi-droplet"></i> {{ $member->blood_group }}</div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="card-footer-section">
                <div class="qr-code">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ urlencode($member->member_id) }}" alt="QR Code">
                </div>
                <div class="validity">
                    <small>Valid Until</small>
                    <strong>{{ date('M Y', strtotime('+2 years')) }}</strong>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>ID Card Information</h6>
                <p class="text-muted small mb-3">
                    This ID card is the property of {{ config('app.name') }}. If found, please return to the foundation office.
                </p>
                <ul class="list-unstyled mb-0 text-muted small">
                    <li class="mb-2"><i class="bi bi-check2 me-2 text-success"></i>Show this card for verification at foundation events</li>
                    <li class="mb-2"><i class="bi bi-check2 me-2 text-success"></i>Present this card for member-only benefits</li>
                    <li><i class="bi bi-check2 me-2 text-success"></i>Scan the QR code to verify membership</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.member-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 25px;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
}
.member-card::before {
    content: '';
    position: absolute;
    top: -60px;
    right: -60px;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}
.member-card::after {
    content: '';
    position: absolute;
    bottom: -100px;
    left: -40px;
    width: 250px;
    height: 250px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}
.card-header-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
    position: relative;
    z-index: 1;
}
.logo-section {
    display: flex;
    align-items: center;
    gap: 10px;
}
.logo-section i {
    font-size: 2rem;
}
.logo-section strong {
    display: block;
    font-size: 14px;
}
.logo-section small {
    font-size: 10px;
    opacity: 0.8;
}
.member-badge {
    background: rgba(255,255,255,0.2);
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.card-body-section {
    display: flex;
    gap: 20px;
    position: relative;
    z-index: 1;
}
.photo-section img {
    width: 100px;
    height: 120px;
    object-fit: cover;
    border-radius: 10px;
    border: 3px solid white;
}
.info-section {
    flex: 1;
}
.name {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 5px;
}
.designation {
    font-size: 12px;
    opacity: 0.9;
    margin-bottom: 15px;
}
.details {
    font-size: 11px;
    line-height: 1.8;
}
.details i {
    width: 20px;
    opacity: 0.8;
}
.card-footer-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    position: relative;
    z-index: 1;
}
.qr-code {
    background: white;
    padding: 8px;
    border-radius: 8px;
}
.qr-code img {
    display: block;
    width: 60px;
    height: 60px;
}
.validity {
    text-align: right;
}
.validity small {
    display: block;
    font-size: 10px;
    opacity: 0.8;
}
.validity strong {
    font-size: 14px;
}
@media print {
    .navbar, .sidebar, .btn, .card {
        display: none !important;
    }
    .member-card {
        box-shadow: none;
        border-radius: 0;
    }
    body {
        background: white;
    }
}
</style>
@endsection
