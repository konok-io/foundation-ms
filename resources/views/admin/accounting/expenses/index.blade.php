@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item"><a href="#">Accounting</a></li><li class="breadcrumb-item active">Expense</li>')

@section('page_actions')
@can('expenses.create')
<a href="{{ route('admin.expenses.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-2"></i>Add Expense
</a>
@endcan
@endsection

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-arrow-up-circle text-danger" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['total'], 2) }} SAR</h3>
                        <p class="text-muted mb-0">Total Expense</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-file-text text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['count']) }}</h3>
                        <p class="text-muted mb-0">Total Vouchers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="category_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search voucher..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Voucher No</th>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Payee</th>
                        <th>Amount</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td>
                            <a href="{{ route('admin.expenses.show', $expense) }}" class="text-decoration-none">
                                <strong>{{ $expense->voucher_no }}</strong>
                            </a>
                        </td>
                        <td>{{ $expense->date->format('M d, Y') }}</td>
                        <td>{{ $expense->category->name ?? 'N/A' }}</td>
                        <td>{{ $expense->payee_name ?? '-' }}</td>
                        <td><strong class="text-danger">{{ number_format($expense->amount, 2) }}</strong></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.expenses.show', $expense) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                @can('expenses.edit')
                                <a href="{{ route('admin.expenses.edit', $expense) }}" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('expenses.delete')
                                <form action="{{ route('admin.expenses.destroy', $expense) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger delete-btn"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No expense records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $expenses->withQueryString()->links() }}
    </div>
</div>
@endsection
