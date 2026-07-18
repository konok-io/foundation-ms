@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Payment Cancelled</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-x-circle text-warning" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h3 class="mb-3">{{ $message ?? 'Your payment was cancelled.' }}</h3>
                    
                    <p class="text-muted">No charges have been made to your account. You can try again from your dashboard.</p>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        <a href="{{ route('member.dashboard') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                        </a>
                        <a href="{{ route('member.payments') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-credit-card me-2"></i>View Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
