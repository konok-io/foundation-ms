@extends('member.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">My Payments</h4>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Payment History</h6>
    </div>
    <div class="card-body">
        @if(count($payments ?? []) > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Receipt No</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td><strong>{{ $payment->receipt_no }}</strong></td>
                        <td>{{ $payment->created_at->format('M d, Y') }}</td>
                        <td>{{ ucfirst($payment->type) }}</td>
                        <td>{{ number_format($payment->amount, 2) }} {{ config('app.currency', 'SAR') }}</td>
                        <td>{{ ucfirst($payment->payment_method) }}</td>
                        <td>
                            <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
            <h5 class="mt-3 text-muted">No Payments Found</h5>
            <p class="text-muted">Your payment history will appear here once you make payments.</p>
        </div>
        @endif
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6>Payment Summary</h6>
                <table class="table table-sm">
                    <tr>
                        <td>Total Paid</td>
                        <td class="text-end fw-bold text-success">0.00 {{ config('app.currency', 'SAR') }}</td>
                    </tr>
                    <tr>
                        <td>Total Payments</td>
                        <td class="text-end fw-bold">0</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6>Due Summary</h6>
                <table class="table table-sm">
                    <tr>
                        <td>Pending Dues</td>
                        <td class="text-end fw-bold text-danger">0.00 {{ config('app.currency', 'SAR') }}</td>
                    </tr>
                    <tr>
                        <td>Overdue</td>
                        <td class="text-end fw-bold">0.00 {{ config('app.currency', 'SAR') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
