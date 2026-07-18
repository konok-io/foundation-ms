@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.expenses.index') }}">Expense</a></li>
<li class="breadcrumb-item active">{{ $expense->voucher_no }}</li>

@section('page_actions')
<div class="btn-group">
    @can('expenses.edit')
    <a href="{{ route('admin.expenses.edit', $expense) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-2"></i>Edit
    </a>
    @endcan
    <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Expense Voucher</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h3 class="text-primary">{{ $expense->voucher_no }}</h3>
                </div>
                
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-light" style="width: 30%;">Category</th>
                        <td>{{ $expense->category->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Date</th>
                        <td>{{ $expense->date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Amount</th>
                        <td><strong class="text-danger fs-5">{{ number_format($expense->amount, 2) }} {{ $expense->currency }}</strong></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Payment Method</th>
                        <td>{{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Payee</th>
                        <td>{{ $expense->payee_name ?? '-' }}</td>
                    </tr>
                    @if($expense->reference_no)
                    <tr>
                        <th class="bg-light">Reference No</th>
                        <td>{{ $expense->reference_no }}</td>
                    </tr>
                    @endif
                    @if($expense->description)
                    <tr>
                        <th class="bg-light">Description</th>
                        <td>{{ $expense->description }}</td>
                    </tr>
                    @endif
                </table>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><small>Created: {{ $expense->created_at->format('d M Y H:i') }}</small></p>
                        @if($expense->creator)
                        <p class="text-muted mb-1"><small>By: {{ $expense->creator->name }}</small></p>
                        @endif
                        @if($expense->approver)
                        <p class="text-muted mb-0"><small>Approved By: {{ $expense->approver->name }}</small></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
