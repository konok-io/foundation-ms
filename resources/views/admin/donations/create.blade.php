@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.donations.index') }}">Donations</a></li>
<li class="breadcrumb-item active">Create</li>

@section('page_actions')
<a href="{{ route('admin.donations.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-2"></i>Back
</a>
@endsection

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add Donation</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.donations.store') }}" method="POST" data-loading>
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="donor_name" class="form-label">Donor Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('donor_name') is-invalid @enderror" id="donor_name" name="donor_name" value="{{ old('donor_name') }}" required>
                                @error('donor_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="donor_name_bn" class="form-label">Donor Name (Bangla)</label>
                                <input type="text" class="form-control" id="donor_name_bn" name="donor_name_bn" value="{{ old('donor_name_bn') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount (SAR) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="1" required>
                                @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="purpose" class="form-label">Purpose <span class="text-danger">*</span></label>
                                <select class="form-select @error('purpose') is-invalid @enderror" id="purpose" name="purpose" required>
                                    <option value="">Select Purpose</option>
                                    @foreach($purposes as $key => $purpose)
                                    <option value="{{ $key }}" {{ old('purpose') == $key ? 'selected' : '' }}>{{ $purpose }}</option>
                                    @endforeach
                                </select>
                                @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3" id="purpose_other_field" style="display: none;">
                        <label for="purpose_other" class="form-label">Specify Purpose</label>
                        <input type="text" class="form-control" id="purpose_other" name="purpose_other" value="{{ old('purpose_other') }}">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                    <option value="">Select Method</option>
                                    @foreach($paymentMethods as $key => $method)
                                    <option value="{{ $key }}" {{ old('payment_method') == $key ? 'selected' : '' }}>{{ $method }}</option>
                                    @endforeach
                                </select>
                                @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}" {{ old('status', 'pending') == $key ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_anonymous" id="is_anonymous" value="1" {{ old('is_anonymous') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_anonymous">
                                Make this an anonymous donation
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="2">{{ old('message') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Save Donation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('purpose')?.addEventListener('change', function() {
        document.getElementById('purpose_other_field').style.display = this.value === 'other' ? 'block' : 'none';
    });
</script>
@endpush
