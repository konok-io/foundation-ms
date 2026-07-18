@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
<li class="breadcrumb-item active">Details</li>

@section('page_actions')
<div class="btn-group">
    @if($payment->isRefundable)
    @can('payments.refund')
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#refundModal">
        <i class="bi bi-arrow-counterclockwise me-2"></i>Refund
    </button>
    @endcan
    @endif
    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Payment Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted" style="width: 150px;">Payment ID</td>
                        <td><strong>{{ $payment->payment_id }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Type</td>
                        <td>{{ $types[$payment->type] ?? $payment->type }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Amount</td>
                        <td><strong class="text-success">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Gateway</td>
                        <td>
                            @if($payment->gateway)
                            <span class="badge bg-{{ $payment->gateway === 'stripe' ? 'info' : 'primary' }}">
                                {{ ucfirst($payment->gateway) }}
                            </span>
                            @else
                            Manual
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'failed' ? 'danger' : ($payment->status === 'refunded' ? 'warning' : 'secondary')) }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Transaction ID</td>
                        <td><code>{{ $payment->gateway_transaction_id ?? 'N/A' }}</code></td>
                    </tr>
                </table>
            </div>
        </div>

        @if($payment->member)
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Member Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted" style="width: 150px;">Member ID</td>
                        <td>
                            <a href="{{ route('admin.members.show', $payment->member) }}">
                                {{ $payment->member->member_id }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Name</td>
                        <td>{{ $payment->member->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>{{ $payment->member->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Phone</td>
                        <td>{{ $payment->member->mobile ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @endif

        @if($payment->failure_reason)
        <div class="card mb-4 border-danger">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0">Failure Reason</h6>
            </div>
            <div class="card-body">
                <p class="mb-0 text-danger">{{ $payment->failure_reason }}</p>
            </div>
        </div>
        @endif

        @if($payment->status === 'refunded')
        <div class="card mb-4 border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">Refund Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted" style="width: 150px;">Refund ID</td>
                        <td><code>{{ $payment->refund_id }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Refund Amount</td>
                        <td><strong>{{ number_format($payment->refund_amount, 2) }} {{ $payment->currency }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Refund Date</td>
                        <td>{{ $payment->refunded_at?->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Reason</td>
                        <td>{{ $payment->refund_reason }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Timeline</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Created</td>
                        <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @if($payment->paid_at)
                    <tr>
                        <td class="text-muted">Paid At</td>
                        <td>{{ $payment->paid_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @endif
                    @if($payment->creator)
                    <tr>
                        <td class="text-muted">Created By</td>
                        <td>{{ $payment->creator->name }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
@if($payment->isRefundable)
<div class="modal fade" id="refundModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.payments.refund', $payment) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Process Refund</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to refund this payment?</p>
                    <table class="table table-sm">
                        <tr>
                            <td>Amount</td>
                            <td><strong>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</strong></td>
                        </tr>
                    </table>
                    <div class="mb-3">
                        <label class="form-label">Refund Amount (optional)</label>
                        <input type="number" class="form-control" name="amount" value="{{ $payment->amount }}" step="0.01" max="{{ $payment->amount }}">
                        <small class="text-muted">Leave as full amount for complete refund</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Process Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
