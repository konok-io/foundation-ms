@extends('frontend.layouts.premium')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white text-center">
                        <h4 class="mb-0"><i class="bi bi-check-circle-fill me-2"></i>Payment Verified</h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h2 class="text-success">{{ number_format($payment->amount, 2) }} SAR</h2>
                            <p class="text-muted">Payment Amount</p>
                        </div>
                        
                        <table class="table table-bordered">
                            <tr>
                                <th class="bg-light">Receipt Number</th>
                                <td><strong>{{ $payment->receipt_number }}</strong></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Member</th>
                                <td>
                                    <a href="{{ route('verify.member', $payment->member?->member_id) }}">
                                        {{ $payment->member?->name ?? 'N/A' }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Payment Type</th>
                                <td>{{ $payment->type_label }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Payment Date</th>
                                <td>{{ $payment->payment_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Payment Method</th>
                                <td>{{ $payment->payment_method_label }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Status</th>
                                <td>
                                    @if($payment->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                    @elseif($payment->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @else
                                    <span class="badge bg-danger">Failed</span>
                                    @endif
                                </td>
                            </tr>
                            @if($payment->transaction_id)
                            <tr>
                                <th class="bg-light">Transaction ID</th>
                                <td>{{ $payment->transaction_id }}</td>
                            </tr>
                            @endif
                            @if($payment->notes)
                            <tr>
                                <th class="bg-light">Notes</th>
                                <td>{{ $payment->notes }}</td>
                            </tr>
                            @endif
                        </table>
                        
                        <div class="alert alert-success mt-4 text-center">
                            <i class="bi bi-shield-check me-2"></i>
                            This payment has been verified as a legitimate transaction.
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <small class="text-muted">
                            Verified at: {{ now()->format('d M Y H:i:s') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
