@extends('admin.layouts.app')

@section('content')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
@endsection

@section('page_actions')
@can('payments.create')
<a href="{{ route('admin.payments.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-2"></i>Create Payment
</a>
@endcan
@can('payments.export')
<a href="{{ route('admin.payments.export') }}" class="btn btn-outline-success">
    <i class="bi bi-download me-2"></i>Export CSV
</a>
@endcan
@endsection

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-credit-card text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['total'], 2) }}</h3>
                        <p class="text-muted mb-0">Total Amount (SAR)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-check-circle text-success" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['completed'], 2) }}</h3>
                        <p class="text-muted mb-0">Completed (SAR)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-hourglass-split text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['pending'], 2) }}</h3>
                        <p class="text-muted mb-0">Pending (SAR)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-arrow-counterclockwise text-info" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['refunded'], 2) }}</h3>
                        <p class="text-muted mb-0">Refunded (SAR)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Payment History</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.payments.index') }}" class="d-flex gap-2 flex-wrap">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        @foreach($statuses as $key => $status)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    <select name="gateway" class="form-select" onchange="this.form.submit()">
                        <option value="">All Gateways</option>
                        @foreach($gateways as $key => $gateway)
                        <option value="{{ $key }}" {{ request('gateway') == $key ? 'selected' : '' }}>{{ $gateway }}</option>
                        @endforeach
                    </select>
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        @foreach($types as $key => $type)
                        <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Member</th>
                        <th>Type</th>
                        <th>Gateway</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>
                            <a href="{{ route('admin.payments.show', $payment) }}" class="text-decoration-none">
                                <strong>{{ $payment->payment_id }}</strong>
                            </a>
                        </td>
                        <td>
                            @if($payment->member)
                            <a href="{{ route('admin.members.show', $payment->member) }}">
                                {{ $payment->member->name }}
                            </a>
                            @else
                            <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>{{ $types[$payment->type] ?? $payment->type }}</td>
                        <td>
                            @if($payment->gateway)
                            <span class="badge bg-{{ $payment->gateway === 'stripe' ? 'info' : 'primary' }}">
                                {{ ucfirst($payment->gateway) }}
                            </span>
                            @else
                            <span class="text-muted">Manual</span>
                            @endif
                        </td>
                        <td><strong>{{ number_format($payment->amount, 2) }}</strong></td>
                        <td>
                            <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'failed' ? 'danger' : ($payment->status === 'refunded' ? 'warning' : 'secondary')) }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>
                            <small>{{ $payment->created_at->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="bi bi-credit-card" style="font-size: 3rem;"></i>
                            <p class="mt-2">No payments found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $payments->withQueryString()->links() }}
    </div>
</div>
@endsection
