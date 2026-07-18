@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.contributions.index') }}">Contributions</a></li>
<li class="breadcrumb-item active">Edit</li>

@section('page_actions')
<div class="btn-group">
    <a href="{{ route('admin.contributions.show', $contribution) }}" class="btn btn-outline-info">
        <i class="bi bi-eye me-2"></i>View
    </a>
    <a href="{{ route('admin.contributions.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Contribution</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.contributions.update', $contribution) }}" method="POST" data-loading>
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="member_id" class="form-label">Member <span class="text-danger">*</span></label>
                                <select class="form-select @error('member_id') is-invalid @enderror" id="member_id" name="member_id" required {{ $contribution->status === 'paid' ? 'disabled' : '' }}>
                                    <option value="">Select Member</option>
                                    @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id', $contribution->member_id) == $member->id ? 'selected' : '' }}>
                                        {{ $member->member_id }} - {{ $member->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('member_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($contribution->status === 'paid')
                                <input type="hidden" name="member_id" value="{{ $contribution->member_id }}">
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="year" class="form-label">Year <span class="text-danger">*</span></label>
                                <select class="form-select @error('year') is-invalid @enderror" id="year" name="year" required {{ $contribution->status === 'paid' ? 'disabled' : '' }}>
                                    @foreach($years as $year)
                                    <option value="{{ $year }}" {{ old('year', $contribution->year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                                @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($contribution->status === 'paid')
                                <input type="hidden" name="year" value="{{ $contribution->year }}">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="month" class="form-label">Month <span class="text-danger">*</span></label>
                                <select class="form-select @error('month') is-invalid @enderror" id="month" name="month" required {{ $contribution->status === 'paid' ? 'disabled' : '' }}>
                                    @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ old('month', $contribution->month) == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                    @endforeach
                                </select>
                                @error('month')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($contribution->status === 'paid')
                                <input type="hidden" name="month" value="{{ $contribution->month }}">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount (SAR) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $contribution->amount) }}" step="0.01" min="0" required>
                                @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date', $contribution->due_date?->format('Y-m-d')) }}">
                                @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    @foreach($statuses as $key => $statusName)
                                    <option value="{{ $key }}" {{ old('status', $contribution->status) == $key ? 'selected' : '' }}>{{ $statusName }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h6>Payment Information</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method">
                                    <option value="">Select Method</option>
                                    @foreach($paymentMethods as $key => $method)
                                    <option value="{{ $key }}" {{ old('payment_method', $contribution->payment_method) == $key ? 'selected' : '' }}>{{ $method }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="transaction_id" class="form-label">Transaction ID</label>
                                <input type="text" class="form-control" id="transaction_id" name="transaction_id" value="{{ old('transaction_id', $contribution->transaction_id) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $contribution->notes) }}</textarea>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Update Contribution
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
