@extends('member.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">My Donations</h4>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Donation History</h6>
    </div>
    <div class="card-body">
        @if(count($donations ?? []) > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Purpose</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donations as $donation)
                    <tr>
                        <td>{{ $donation->created_at->format('M d, Y') }}</td>
                        <td>{{ $donation->purpose ?? 'General Donation' }}</td>
                        <td>{{ number_format($donation->amount, 2) }} {{ config('app.currency', 'SAR') }}</td>
                        <td>{{ ucfirst($donation->payment_method ?? 'N/A') }}</td>
                        <td>
                            @if($donation->receipt)
                            <a href="#" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download"></i> Download
                            </a>
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-heart text-muted" style="font-size: 4rem;"></i>
            <h5 class="mt-3 text-muted">No Donations Yet</h5>
            <p class="text-muted">Your donation history will appear here when you make donations.</p>
            <a href="#" class="btn btn-primary mt-2">
                <i class="bi bi-heart me-2"></i>Make a Donation
            </a>
        </div>
        @endif
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h6><i class="bi bi-heart-fill text-danger me-2"></i>Thank You for Your Support</h6>
        <p class="text-muted mb-0">
            Your generous donations help us continue our mission of serving the community. 
            Every contribution, no matter how small, makes a difference in someone's life.
        </p>
    </div>
</div>
@endsection
