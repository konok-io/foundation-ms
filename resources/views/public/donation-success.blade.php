@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-check-circle me-2"></i>Thank You!</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-heart-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h3 class="mb-3">Your donation has been received!</h3>
                    <p class="text-muted">Thank you for your generous contribution. Your support makes a real difference.</p>
                    
                    @if(isset($donation) && $donation)
                    <div class="alert alert-light border mx-auto" style="max-width: 400px;">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-muted text-start">Amount:</td>
                                <td class="text-end"><strong class="text-success">{{ number_format($donation->amount, 2) }} SAR</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted text-start">Purpose:</td>
                                <td class="text-end">{{ $donation->purpose_label }}</td>
                            </tr>
                        </table>
                    </div>
                    @endif
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-house me-2"></i>Go to Homepage
                        </a>
                        <a href="{{ route('donate') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-heart me-2"></i>Make Another Donation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
