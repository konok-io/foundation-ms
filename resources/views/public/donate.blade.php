@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-heart me-2"></i>Make a Donation</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Your generous donation helps us support those in need. Thank you for your kindness!</p>
                    
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    
                    <form action="{{ route('donation.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="donor_name" class="form-label">Your Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('donor_name') is-invalid @enderror" id="donor_name" name="donor_name" value="{{ old('donor_name') }}" required>
                                    @error('donor_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount (SAR) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', 100) }}" min="1" step="0.01" required>
                                    @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Donation Purpose <span class="text-danger">*</span></label>
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
                        
                        <div class="mb-3" id="purpose_other_field" style="display: none;">
                            <label for="purpose_other" class="form-label">Specify Purpose</label>
                            <input type="text" class="form-control @error('purpose_other') is-invalid @enderror" id="purpose_other" name="purpose_other" value="{{ old('purpose_other') }}">
                            @error('purpose_other')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="gateway" class="form-label">Payment Method</label>
                            <select class="form-select" id="gateway" name="gateway">
                                <option value="">Select Payment Method</option>
                                <option value="stripe">Credit Card (Stripe)</option>
                                <option value="paypal">PayPal</option>
                            </select>
                            <small class="text-muted">Leave empty to pay later via cash or bank transfer</small>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_anonymous" id="is_anonymous" value="1" {{ old('is_anonymous') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_anonymous">
                                    Make this an anonymous donation (your name will not be displayed publicly)
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Message (Optional)</label>
                            <textarea class="form-control" id="message" name="message" rows="3" placeholder="Leave a message with your donation...">{{ old('message') }}</textarea>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-heart-fill me-2"></i>Submit Donation
                            </button>
                        </div>
                    </form>
                </div>
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
