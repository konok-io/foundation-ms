@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.incomes.index') }}">Income</a></li>
<li class="breadcrumb-item active">{{ $income->voucher_no }}</li>

@section('page_actions')
<div class="btn-group">
    @can('incomes.edit')
    <a href="{{ route('admin.incomes.edit', $income) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-2"></i>Edit
    </a>
    @endcan
    <a href="{{ route('admin.incomes.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Income Voucher</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h3 class="text-primary">{{ $income->voucher_no }}</h3>
                </div>
                
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-light" style="width: 30%;">Category</th>
                        <td>{{ $income->category->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Date</th>
                        <td>{{ $income->date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Amount</th>
                        <td><strong class="text-success fs-5">{{ number_format($income->amount, 2) }} {{ $income->currency }}</strong></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Payment Method</th>
                        <td>{{ ucfirst(str_replace('_', ' ', $income->payment_method)) }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Received From</th>
                        <td>{{ $income->received_from ?? '-' }}</td>
                    </tr>
                    @if($income->reference_no)
                    <tr>
                        <th class="bg-light">Reference No</th>
                        <td>{{ $income->reference_no }}</td>
                    </tr>
                    @endif
                    @if($income->description)
                    <tr>
                        <th class="bg-light">Description</th>
                        <td>{{ $income->description }}</td>
                    </tr>
                    @endif
                </table>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><small>Created: {{ $income->created_at->format('d M Y H:i') }}</small></p>
                        @if($income->creator)
                        <p class="text-muted mb-0"><small>By: {{ $income->creator->name }}</small></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
