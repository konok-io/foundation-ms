@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item"><a href="#">Accounting</a></li><li class="breadcrumb-item active">Cash Book</li>')

@section('page_actions')
@can('reports.view')
<a href="{{ route('admin.reports.income-statement') }}" class="btn btn-outline-info">
    <i class="bi bi-graph-up me-2"></i>Income Statement
</a>
@endcan
@can('ledger.export')
<a href="{{ route('admin.ledger.export', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="btn btn-outline-success">
    <i class="bi bi-download me-2"></i>Export
</a>
@endcan
@endsection

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Opening Balance</h6>
                <h4 class="mb-0 {{ $openingBalance >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($openingBalance, 2) }} SAR</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Total Income</h6>
                <h4 class="mb-0 text-success">{{ number_format($incomeTotal, 2) }} SAR</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Total Expense</h6>
                <h4 class="mb-0 text-danger">{{ number_format($expenseTotal, 2) }} SAR</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Closing Balance</h6>
                <h4 class="mb-0 {{ $closingBalance >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($closingBalance, 2) }} SAR</h4>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Voucher</th>
                        <th>Description</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Credit</th>
                        <th class="text-end">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                    <tr>
                        <td>{{ $entry->date->format('d M Y') }}</td>
                        <td><code>{{ $entry->voucher_no ?? '-' }}</code></td>
                        <td>{{ $entry->description ?? '-' }}</td>
                        <td class="text-end text-danger">{{ $entry->debit > 0 ? number_format($entry->debit, 2) : '-' }}</td>
                        <td class="text-end text-success">{{ $entry->credit > 0 ? number_format($entry->credit, 2) : '-' }}</td>
                        <td class="text-end fw-bold {{ $entry->running_balance >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($entry->running_balance, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No ledger entries found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-end">Totals:</td>
                        <td class="text-end text-danger">{{ number_format($expenseTotal, 2) }}</td>
                        <td class="text-end text-success">{{ number_format($incomeTotal, 2) }}</td>
                        <td class="text-end {{ $closingBalance >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($closingBalance, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
