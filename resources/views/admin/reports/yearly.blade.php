@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li><li class="breadcrumb-item active">Yearly Report</li>')

@section('page_actions')
<a href="{{ route('admin.reports.yearly', ['year' => $year, 'pdf' => true]) }}" class="btn btn-outline-danger">
    <i class="bi bi-file-pdf me-2"></i>Export PDF
</a>
@endsection

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Yearly Financial Report</h5>
        <p class="text-muted mb-0">Year: {{ $year }}</p>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Year</label>
                <select name="year" class="form-select">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <!-- Summary -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-success bg-opacity-10">
                    <div class="card-body text-center">
                        <h3 class="text-success">{{ number_format($totalIncome, 2) }} SAR</h3>
                        <p class="mb-0">Total Income</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger bg-opacity-10">
                    <div class="card-body text-center">
                        <h3 class="text-danger">{{ number_format($totalExpense, 2) }} SAR</h3>
                        <p class="mb-0">Total Expense</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-{{ $netBalance >= 0 ? 'success' : 'danger' }} bg-opacity-10">
                    <div class="card-body text-center">
                        <h3 class="text-{{ $netBalance >= 0 ? 'success' : 'danger' }}">{{ number_format($netBalance, 2) }} SAR</h3>
                        <p class="mb-0">Net Balance</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Breakdown -->
        <h5>Monthly Breakdown</h5>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Month</th>
                    <th class="text-end">Income</th>
                    <th class="text-end">Expense</th>
                    <th class="text-end">Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach(range(1, 12) as $m)
                <tr>
                    <td>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</td>
                    <td class="text-end text-success">{{ number_format($incomeByMonth[$m] ?? 0, 2) }}</td>
                    <td class="text-end text-danger">{{ number_format($expenseByMonth[$m] ?? 0, 2) }}</td>
                    <td class="text-end fw-bold">{{ number_format(($incomeByMonth[$m] ?? 0) - ($expenseByMonth[$m] ?? 0), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="text-end text-success"><strong>{{ number_format($totalIncome, 2) }}</strong></td>
                    <td class="text-end text-danger"><strong>{{ number_format($totalExpense, 2) }}</strong></td>
                    <td class="text-end fw-bold">{{ number_format($netBalance, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
