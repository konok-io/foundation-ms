@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if($verification['valid'])
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-check-circle me-2"></i>Receipt Verified</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <strong>{{ $verification['message'] }}</strong>
                    </div>
                    
                    @php $receipt = $verification['receipt']; @endphp
                    
                    <table class="table table-bordered">
                        <tr>
                            <th class="bg-light" style="width: 40%;">Receipt Number</th>
                            <td><strong class="text-success">{{ $receipt->receipt_no }}</strong></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Type</th>
                            <td>{{ str_replace('_', ' ', ucfirst($receipt->type)) }}</td>
                        </tr>
                        @if($receipt->member)
                        <tr>
                            <th class="bg-light">Member Name</th>
                            <td>{{ $receipt->member->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Member ID</th>
                            <td>{{ $receipt->member->member_id }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th class="bg-light">Amount</th>
                            <td><strong class="text-success">{{ number_format($receipt->amount, 2) }} {{ $receipt->currency }}</strong></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Payment Method</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $receipt->payment_method)) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Payment Date</th>
                            <td>{{ $receipt->paid_at?->format('d M Y, h:i A') }}</td>
                        </tr>
                        @if($receipt->purpose)
                        <tr>
                            <th class="bg-light">Purpose</th>
                            <td>{{ $receipt->purpose }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th class="bg-light">Issued By</th>
                            <td>{{ $receipt->creator?->name ?? 'System' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Created On</th>
                            <td>{{ $receipt->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="bi bi-house me-2"></i>Go to Homepage
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="bi bi-x-circle me-2"></i>Verification Failed</h4>
                </div>
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">Invalid Receipt</h4>
                    <p class="text-muted">{{ $verification['message'] }}</p>
                    <p class="small text-muted">Receipt Number: {{ request('receipt_no') }}</p>
                    
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-house me-2"></i>Go to Homepage
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
