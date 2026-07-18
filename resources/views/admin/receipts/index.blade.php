@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item"><a href="#">Receipts</a></li>')

@section('page_actions')
@can('receipts.export')
<a href="{{ route('admin.receipts.export') }}" class="btn btn-outline-success">
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
                            <i class="bi bi-receipt text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
                        <p class="text-muted mb-0">Total Receipts</p>
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
                            <i class="bi bi-cash text-success" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['total_amount'], 2) }}</h3>
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
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-printer text-info" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['printed']) }}</h3>
                        <p class="text-muted mb-0">Printed</p>
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
                            <i class="bi bi-envelope text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['emailed']) }}</h3>
                        <p class="text-muted mb-0">Emailed</p>
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
                <h5 class="mb-0">All Receipts</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.receipts.index') }}" class="d-flex gap-2 flex-wrap">
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        @foreach($types as $key => $type)
                        <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
                    <input type="text" name="search" class="form-control" placeholder="Receipt No..." value="{{ request('search') }}">
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
                        <th>Receipt No</th>
                        <th>Member</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Paid Date</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receipts as $receipt)
                    <tr>
                        <td>
                            <a href="{{ route('admin.receipts.show', $receipt) }}" class="text-decoration-none">
                                <strong>{{ $receipt->receipt_no }}</strong>
                            </a>
                        </td>
                        <td>
                            @if($receipt->member)
                            <a href="{{ route('admin.members.show', $receipt->member) }}">
                                {{ $receipt->member->name }}
                            </a>
                            @else
                            <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>{{ $types[$receipt->type] ?? $receipt->type }}</td>
                        <td><strong>{{ number_format($receipt->amount, 2) }}</strong></td>
                        <td>{{ ucfirst($receipt->payment_method) }}</td>
                        <td>{{ $receipt->paid_at?->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.receipts.show', $receipt) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('receipts.download')
                                <a href="{{ route('admin.receipts.download', $receipt) }}" class="btn btn-outline-success" title="Download PDF">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="{{ route('admin.receipts.print', $receipt) }}" target="_blank" class="btn btn-outline-info" title="Print">
                                    <i class="bi bi-printer"></i>
                                </a>
                                @endcan
                                @can('receipts.email')
                                <a href="{{ route('admin.receipts.email', $receipt) }}" class="btn btn-outline-warning" title="Send Email">
                                    <i class="bi bi-envelope"></i>
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-receipt" style="font-size: 3rem;"></i>
                            <p class="mt-2">No receipts found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $receipts->withQueryString()->links() }}
    </div>
</div>
@endsection
