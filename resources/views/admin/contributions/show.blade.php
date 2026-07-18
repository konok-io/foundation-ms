@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.contributions.index') }}">Contributions</a></li>
<li class="breadcrumb-item active">Details</li>

@section('page_actions')
<div class="btn-group">
    @if($contribution->status !== 'paid')
    @can('contributions.edit')
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">
        <i class="bi bi-cash me-2"></i>Record Payment
    </button>
    @endcan
    @endif
    @can('contributions.edit')
    <a href="{{ route('admin.contributions.edit', $contribution) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-2"></i>Edit
    </a>
    @endcan
    <a href="{{ route('admin.contributions.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Contribution Details</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Member</td>
                                <td>
                                    <a href="{{ route('admin.members.show', $contribution->member) }}">
                                        <strong>{{ $contribution->member->member_id }}</strong> - {{ $contribution->member->name }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Period</td>
                                <td><strong>{{ $contribution->month_name }} {{ $contribution->year }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Amount</td>
                                <td><strong>{{ number_format($contribution->amount, 2) }} SAR</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Paid Amount</td>
                                <td class="text-success"><strong>{{ number_format($contribution->paid_amount, 2) }} SAR</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Due Amount</td>
                                <td class="text-danger"><strong>{{ number_format($contribution->due_amount, 2) }} SAR</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Status</td>
                                <td>
                                    <span class="badge bg-{{ $contribution->status === 'paid' ? 'success' : ($contribution->status === 'overdue' ? 'danger' : ($contribution->status === 'partial' ? 'warning' : 'secondary')) }}">
                                        {{ ucfirst($contribution->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Due Date</td>
                                <td>{{ $contribution->due_date?->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Paid Date</td>
                                <td>{{ $contribution->paid_date?->format('M d, Y') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Receipt No</td>
                                <td><strong>{{ $contribution->receipt_no ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Payment Method</td>
                                <td>{{ $contribution->payment_method ? ucfirst(str_replace('_', ' ', $contribution->payment_method)) : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($contribution->notes)
                <hr>
                <h6>Notes</h6>
                <p class="mb-0">{{ $contribution->notes }}</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Payment Progress</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Progress</span>
                        <span>{{ round(($contribution->paid_amount / $contribution->amount) * 100, 1) }}%</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" style="width: {{ ($contribution->paid_amount / $contribution->amount) * 100 }}%"></div>
                    </div>
                </div>
                
                <table class="table table-sm">
                    <tr>
                        <td>Total Amount</td>
                        <td class="text-end">{{ number_format($contribution->amount, 2) }} SAR</td>
                    </tr>
                    <tr>
                        <td>Paid</td>
                        <td class="text-end text-success">{{ number_format($contribution->paid_amount, 2) }} SAR</td>
                    </tr>
                    <tr class="fw-bold">
                        <td>Remaining</td>
                        <td class="text-end text-danger">{{ number_format($contribution->due_amount, 2) }} SAR</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Audit Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">Created</td>
                        <td>{{ $contribution->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @if($contribution->creator)
                    <tr>
                        <td class="text-muted">By</td>
                        <td>{{ $contribution->creator->name }}</td>
                    </tr>
                    @endif
                    @if($contribution->paid_date)
                    <tr>
                        <td class="text-muted">Payment Date</td>
                        <td>{{ $contribution->paid_date->format('M d, Y H:i') }}</td>
                    </tr>
                    @endif
                    @if($contribution->payer)
                    <tr>
                        <td class="text-muted">Payment By</td>
                        <td>{{ $contribution->payer->name }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
@if($contribution->status !== 'paid')
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.contributions.record-payment', $contribution) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Amount Due:</strong> {{ number_format($contribution->remaining_amount, 2) }} SAR</p>
                    <div class="mb-3">
                        <label class="form-label">Payment Amount</label>
                        <input type="number" class="form-control" name="paid_amount" value="{{ $contribution->remaining_amount }}" step="0.01" min="0" max="{{ $contribution->remaining_amount }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" name="payment_method" required>
                            <option value="">Select Method</option>
                            @foreach($paymentMethods as $key => $method)
                            <option value="{{ $key }}">{{ $method }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transaction ID</label>
                        <input type="text" class="form-control" name="transaction_id">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
