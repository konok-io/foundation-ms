@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.emergency-collections.index') }}">Emergency Collections</a></li>
<li class="breadcrumb-item active">Details</li>

@section('page_actions')
<div class="btn-group">
    @if($collection->status === 'active')
    @can('emergency_collections.edit')
    <a href="{{ route('admin.emergency-collections.assign-members', $collection) }}" class="btn btn-outline-primary">
        <i class="bi bi-people me-2"></i>Assign Members
    </a>
    @endcan
    @can('emergency_collections.edit')
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkPaymentModal">
        <i class="bi bi-cash me-2"></i>Bulk Payment
    </button>
    <a href="{{ route('admin.emergency-collections.close-collection', $collection) }}" class="btn btn-warning">
        <i class="bi bi-check-circle me-2"></i>Close
    </a>
    @endcan
    @endif
    @can('emergency_collections.edit')
    <a href="{{ route('admin.emergency-collections.edit', $collection) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-2"></i>Edit
    </a>
    @endcan
    <a href="{{ route('admin.emergency-collections.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<div class="row">
    <div class="col-md-4">
        <!-- Collection Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Collection Information</h6>
            </div>
            <div class="card-body">
                <h5>{{ $collection->title }}</h5>
                @if($collection->title_bn)
                <p class="text-muted mb-2">{{ $collection->title_bn }}</p>
                @endif
                <span class="badge bg-{{ $collection->status === 'active' ? 'success' : ($collection->status === 'closed' ? 'info' : 'secondary') }}">
                    {{ ucfirst($collection->status) }}
                </span>
                <span class="badge bg-secondary ms-1">{{ $types[$collection->type] ?? $collection->type }}</span>
                
                @if($collection->description)
                <p class="mt-3 mb-0">{{ $collection->description }}</p>
                @endif
            </div>
        </div>
        
        <!-- Progress -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Collection Progress</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Progress</span>
                        <span class="fw-bold">{{ $collection->progress_percentage }}%</span>
                    </div>
                    <div class="progress" style="height: 15px;">
                        <div class="progress-bar bg-success" style="width: {{ $collection->progress_percentage }}%"></div>
                    </div>
                </div>
                
                <table class="table table-sm">
                    <tr>
                        <td>Target Amount</td>
                        <td class="text-end fw-bold">{{ number_format($collection->target_amount, 2) }} SAR</td>
                    </tr>
                    <tr>
                        <td>Collected</td>
                        <td class="text-end text-success fw-bold">{{ number_format($collection->collected_amount, 2) }} SAR</td>
                    </tr>
                    <tr>
                        <td>Remaining</td>
                        <td class="text-end text-danger fw-bold">{{ number_format($collection->remaining_amount, 2) }} SAR</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Schedule -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Schedule</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Start Date</td>
                        <td>{{ $collection->start_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">End Date</td>
                        <td>{{ $collection->end_date?->format('M d, Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Members Assigned</td>
                        <td>{{ $collection->total_members_assigned }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Paid</td>
                        <td class="text-success">{{ $collection->paid_count }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Unpaid</td>
                        <td class="text-danger">{{ $collection->unpaid_count }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Member Payments</h6>
            </div>
            <div class="card-body">
                @if($collection->payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>Member</th>
                                <th>Amount</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Status</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($collection->payments as $payment)
                            <tr>
                                <td><input type="checkbox" class="payment-checkbox" value="{{ $payment->id }}"></td>
                                <td>
                                    <a href="{{ route('admin.members.show', $payment->member) }}">
                                        <strong>{{ $payment->member->member_id }}</strong><br>
                                        <small>{{ $payment->member->name }}</small>
                                    </a>
                                </td>
                                <td>{{ number_format($payment->amount, 2) }}</td>
                                <td class="text-success">{{ number_format($payment->paid_amount, 2) }}</td>
                                <td class="text-danger">{{ number_format($payment->due_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'partial' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment->status !== 'paid')
                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $payment->id }}">
                                        <i class="bi bi-cash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            
                            <!-- Payment Modal -->
                            @if($payment->status !== 'paid')
                            <div class="modal fade" id="paymentModal{{ $payment->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.emergency-collections.record-payment', $payment) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Record Payment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Member:</strong> {{ $payment->member->name }}</p>
                                                <p><strong>Amount Due:</strong> {{ number_format($payment->remaining_amount, 2) }} SAR</p>
                                                <div class="mb-3">
                                                    <label class="form-label">Payment Amount</label>
                                                    <input type="number" class="form-control" name="paid_amount" value="{{ $payment->remaining_amount }}" step="0.01" min="0">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Payment Method</label>
                                                    <select class="form-select" name="payment_method" required>
                                                        <option value="">Select Method</option>
                                                        @foreach($paymentMethods as $key => $method)
                                                        <option value="{{ $key }}">{{ $method }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Transaction ID</label>
                                                    <input type="text" class="form-control" name="transaction_id">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Notes</label>
                                                    <textarea class="form-control" name="notes" rows="2"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">Record Payment</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">No members assigned yet.</p>
                    @can('emergency_collections.edit')
                    <a href="{{ route('admin.emergency-collections.assign-members', $collection) }}" class="btn btn-primary">
                        <i class="bi bi-people me-2"></i>Assign All Active Members
                    </a>
                    @endcan
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bulk Payment Modal -->
<div class="modal fade" id="bulkPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.emergency-collections.bulk-payment') }}" method="POST">
                @csrf
                <input type="hidden" name="collection_id" value="{{ $collection->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Mark selected members as paid for this emergency collection.</p>
                    <input type="hidden" name="payment_ids" id="selectedPaymentIds">
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" name="payment_method" required>
                            <option value="">Select Method</option>
                            @foreach($paymentMethods as $key => $method)
                            <option value="{{ $key }}">{{ $method }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Process Bulk Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.payment-checkbox').forEach(cb => cb.checked = this.checked);
        updateSelectedIds();
    });
    
    document.querySelectorAll('.payment-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSelectedIds);
    });
    
    function updateSelectedIds() {
        const ids = Array.from(document.querySelectorAll('.payment-checkbox:checked')).map(cb => cb.value);
        document.getElementById('selectedPaymentIds').value = ids.join(',');
    }
</script>
@endpush
