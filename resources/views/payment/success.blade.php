@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-check-circle me-2"></i>Payment Successful</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h3 class="mb-3">{{ $message ?? 'Your payment has been processed successfully!' }}</h3>
                    
                    @if(isset($payment) && $payment)
                    <div class="alert alert-light border">
                        <h5 class="mb-3">Payment Details</h5>
                        <table class="table table-borderless mb-0 mx-auto" style="max-width: 400px;">
                            <tr>
                                <td class="text-muted text-start">Payment ID:</td>
                                <td class="text-end"><strong>{{ $payment->payment_id }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted text-start">Amount:</td>
                                <td class="text-end"><strong class="text-success">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted text-start">Status:</td>
                                <td class="text-end">
                                    <span class="badge bg-success">{{ ucfirst($payment->status) }}</span>
                                </td>
                            </tr>
                            @if($payment->paid_at)
                            <tr>
                                <td class="text-muted text-start">Date:</td>
                                <td class="text-end">{{ $payment->paid_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    @endif
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        <a href="{{ route('member.dashboard') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
