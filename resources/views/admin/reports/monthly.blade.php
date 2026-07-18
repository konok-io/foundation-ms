@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item"><a href="#">Reports</a></li><li class="breadcrumb-item active">Monthly Report</li>')

@section('page_actions')
<a href="{{ route('admin.reports.monthly', ['year' => $year, 'month' => $month, 'pdf' => true]) }}" class="btn btn-outline-danger">
    <i class="bi bi-file-pdf me-2"></i>Export PDF
</a>
@endsection

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Monthly Financial Report</h5>
        <p class="text-muted mb-0">{{ $monthName }}</p>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Year</label>
                <select name="year" class="form-select">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Month</label>
                <select name="month" class="form-select">
                    @foreach(range(1, 12) as $m)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <!-- Summary -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success bg-opacity-10">
                    <div class="card-body text-center">
                        <h4 class="text-success">{{ number_format($totalIncome, 2) }} SAR</h4>
                        <p class="mb-0">Total Income</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger bg-opacity-10">
                    <div class="card-body text-center">
                        <h4 class="text-danger">{{ number_format($totalExpense, 2) }} SAR</h4>
                        <p class="mb-0">Total Expense</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary bg-opacity-10">
                    <div class="card-body text-center">
                        <h4 class="text-primary">{{ $incomes->count() }}</h4>
                        <p class="mb-0">Income Vouchers</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning bg-opacity-10">
                    <div class="card-body text-center">
                        <h4 class="text-warning">{{ $expenses->count() }}</h4>
                        <p class="mb-0">Expense Vouchers</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h5 class="text-success">Income by Category</h5>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incomeByCategory as $categoryId => $amount)
                        <tr>
                            <td>{{ $incomes->firstWhere('category_id', $categoryId)?->category->name ?? 'N/A' }}</td>
                            <td class="text-end">{{ number_format($amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-muted">No income</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-success">
                        <tr>
                            <td class="text-end"><strong>Total</strong></td>
                            <td class="text-end"><strong>{{ number_format($totalIncome, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-6">
                <h5 class="text-danger">Expense by Category</h5>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenseByCategory as $categoryId => $amount)
                        <tr>
                            <td>{{ $expenses->firstWhere('category_id', $categoryId)?->category->name ?? 'N/A' }}</td>
                            <td class="text-end">{{ number_format($amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-muted">No expense</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-danger">
                        <tr>
                            <td class="text-end"><strong>Total</strong></td>
                            <td class="text-end"><strong>{{ number_format($totalExpense, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
