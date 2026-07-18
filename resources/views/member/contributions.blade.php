@extends('member.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">My Contributions</h4>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Monthly Contributions</h6>
    </div>
    <div class="card-body">
        @if(count($contributions ?? []) > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Month/Year</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment Date</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contributions as $contribution)
                    <tr>
                        <td>{{ $contribution->month }}/{{ $contribution->year }}</td>
                        <td>{{ number_format($contribution->amount, 2) }} {{ config('app.currency', 'SAR') }}</td>
                        <td>
                            <span class="badge bg-{{ $contribution->status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($contribution->status) }}
                            </span>
                        </td>
                        <td>{{ $contribution->paid_at?->format('M d, Y') ?? '-' }}</td>
                        <td>
                            @if($contribution->receipt)
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download"></i>
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
            <i class="bi bi-wallet2 text-muted" style="font-size: 4rem;"></i>
            <h5 class="mt-3 text-muted">No Contributions Found</h5>
            <p class="text-muted">Your monthly contribution history will appear here.</p>
        </div>
        @endif
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Contribution Summary</h6>
                <table class="table table-sm">
                    <tr>
                        <td>Total Amount Paid</td>
                        <td class="text-end fw-bold">0.00 {{ config('app.currency', 'SAR') }}</td>
                    </tr>
                    <tr>
                        <td>Total Contributions</td>
                        <td class="text-end fw-bold">0</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Pending Dues</h6>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    No pending dues at the moment.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
