@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item"><a href="#">Reports</a></li><li class="breadcrumb-item active">Daily Report</li>')

@section('page_actions')
<a href="{{ route('admin.reports.daily', ['date' => $date, 'pdf' => true]) }}" class="btn btn-outline-danger">
    <i class="bi bi-file-pdf me-2"></i>Export PDF
</a>
@endsection

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daily Financial Report</h5>
        <p class="text-muted mb-0">{{ date('l, d F Y', strtotime($date)) }}</p>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-success bg-opacity-10 border-success">
                    <div class="card-body text-center">
                        <h3 class="text-success">{{ number_format($totalIncome, 2) }} SAR</h3>
                        <p class="mb-0">Total Income</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger bg-opacity-10 border-danger">
                    <div class="card-body text-center">
                        <h3 class="text-danger">{{ number_format($totalExpense, 2) }} SAR</h3>
                        <p class="mb-0">Total Expense</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-{{ $netBalance >= 0 ? 'success' : 'danger' }} bg-opacity-10 border-{{ $netBalance >= 0 ? 'success' : 'danger' }}">
                    <div class="card-body text-center">
                        <h3 class="text-{{ $netBalance >= 0 ? 'success' : 'danger' }}">{{ number_format($netBalance, 2) }} SAR</h3>
                        <p class="mb-0">Net Balance</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h5 class="text-success">Income</h5>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Voucher</th>
                            <th>Category</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incomes as $income)
                        <tr>
                            <td><code>{{ $income->voucher_no }}</code></td>
                            <td>{{ $income->category->name ?? 'N/A' }}</td>
                            <td class="text-end">{{ number_format($income->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No income records</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-success">
                        <tr>
                            <td colspan="2" class="text-end"><strong>Total</strong></td>
                            <td class="text-end"><strong>{{ number_format($totalIncome, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-6">
                <h5 class="text-danger">Expense</h5>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Voucher</th>
                            <th>Category</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                        <tr>
                            <td><code>{{ $expense->voucher_no }}</code></td>
                            <td>{{ $expense->category->name ?? 'N/A' }}</td>
                            <td class="text-end">{{ number_format($expense->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No expense records</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-danger">
                        <tr>
                            <td colspan="2" class="text-end"><strong>Total</strong></td>
                            <td class="text-end"><strong>{{ number_format($totalExpense, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
