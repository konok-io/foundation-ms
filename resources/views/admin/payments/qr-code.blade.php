@extends('admin.layouts.app')

@section('title', 'Payment QR Code')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $page_title ?? 'Payment QR Code' }}</h5>
                </div>
                <div class="card-body text-center">
                    <div class="qr-container p-4 bg-light rounded mb-4">
                        {!! $qrCode !!}
                    </div>
                    
                    <h4 class="mb-2">Receipt: {{ $payment->receipt_number ?? $payment->id }}</h4>
                    <p class="text-muted mb-1">Amount: {{ number_format($payment->amount, 2) }} {{ $payment->currency ?? 'SAR' }}</p>
                    <p class="text-muted">Scan this QR code to verify payment</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.payments.qr-code.download', $payment) }}" class="btn btn-primary">
                            <i class="bi bi-download me-2"></i> Download QR Code
                        </a>
                        <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Back to Payment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
