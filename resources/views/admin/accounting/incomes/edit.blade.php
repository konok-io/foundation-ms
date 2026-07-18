@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.incomes.index') }}">Income</a></li>
<li class="breadcrumb-item active">Edit</li>

@section('page_actions')
<a href="{{ route('admin.incomes.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-2"></i>Back
</a>
@endsection

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Income Voucher</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.incomes.update', $income) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $income->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date', $income->date->format('Y-m-d')) }}" required>
                                @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Amount (SAR) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount', $income->amount) }}" step="0.01" min="1" required>
                                @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" name="payment_method" required>
                                    <option value="">Select Method</option>
                                    <option value="cash" {{ old('payment_method', $income->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bank_transfer" {{ old('payment_method', $income->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="check" {{ old('payment_method', $income->payment_method) == 'check' ? 'selected' : '' }}>Check</option>
                                    <option value="other" {{ old('payment_method', $income->payment_method) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Received From</label>
                        <input type="text" class="form-control" name="received_from" value="{{ old('received_from', $income->received_from) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3">{{ old('description', $income->description) }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Reference No</label>
                        <input type="text" class="form-control" name="reference_no" value="{{ old('reference_no', $income->reference_no) }}">
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Update Voucher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
