@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item"><a href="{{ route('admin.incomes.index') }}">Accounting</a></li><li class="breadcrumb-item active">Income Statement</li>')

@section('page_actions')
<a href="{{ route('admin.ledger.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-2"></i>Back to Cash Book
</a>
@endsection

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Income Statement</h5>
        <p class="text-muted mb-0">{{ date('d M Y', strtotime($dateFrom)) }} - {{ date('d M Y', strtotime($dateTo)) }}</p>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Update</button>
            </div>
        </form>

        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead class="table-success">
                        <tr>
                            <th colspan="2">Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incomes as $income)
                        <tr>
                            <td>{{ $income->name }}</td>
                            <td class="text-end">{{ number_format($income->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-muted text-center">No income records</td>
                        </tr>
                        @endforelse
                        <tr class="table-success fw-bold">
                            <td>Total Income</td>
                            <td class="text-end">{{ number_format($totalIncome, 2) }} SAR</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead class="table-danger">
                        <tr>
                            <th colspan="2">Expenses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                        <tr>
                            <td>{{ $expense->name }}</td>
                            <td class="text-end">{{ number_format($expense->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-muted text-center">No expense records</td>
                        </tr>
                        @endforelse
                        <tr class="table-danger fw-bold">
                            <td>Total Expenses</td>
                            <td class="text-end">{{ number_format($totalExpense, 2) }} SAR</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 mx-auto">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="table-success">Total Income</th>
                            <td class="text-end fw-bold">{{ number_format($totalIncome, 2) }} SAR</td>
                        </tr>
                        <tr>
                            <th class="table-danger">Less: Total Expenses</th>
                            <td class="text-end fw-bold">({{ number_format($totalExpense, 2) }}) SAR</td>
                        </tr>
                        <tr class="{{ $netProfit >= 0 ? 'table-success' : 'table-danger' }} fw-bold fs-5">
                            <th>{{ $netProfit >= 0 ? 'Net Profit' : 'Net Loss' }}</th>
                            <td class="text-end">{{ number_format(abs($netProfit), 2) }} SAR</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
