@extends('admin.layouts.app')

@section('content')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.contributions.index') }}">Contributions</a></li>
@endsection

@section('page_actions')
<div class="btn-group">
    @can('contributions.create')
    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
        <i class="bi bi-plus-lg me-2"></i>Add Contribution
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="{{ route('admin.contributions.create') }}"><i class="bi bi-person me-2"></i>Single Member</a></li>
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#generateModal"><i class="bi bi-calendar-plus me-2"></i>Generate Monthly</a></li>
    </ul>
    @endcan
</div>
@endsection

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-people text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['total_members']) }}</h3>
                        <p class="text-muted mb-0">Total Members</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-cash-stack text-success" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['total_expected'], 2) }}</h3>
                        <p class="text-muted mb-0">Expected (SAR)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-check-circle text-info" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['total_collected'], 2) }}</h3>
                        <p class="text-muted mb-0">Collected (SAR)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-exclamation-triangle text-danger" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['total_due'], 2) }}</h3>
                        <p class="text-muted mb-0">Due (SAR)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Monthly Contributions</h5>
                <small class="text-muted">Collection Rate: <strong class="text-success">{{ $stats['collection_rate'] }}%</strong></small>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.contributions.index') }}" class="d-flex gap-2">
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year', date('Y')) == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                    <select name="month" class="form-select" onchange="this.form.submit()">
                        <option value="">All Months</option>
                        @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        @foreach($statuses as $key => $status)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" class="form-control" placeholder="Search member..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Member</th>
                        <th>Period</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contributions as $contribution)
                    <tr>
                        <td><input type="checkbox" class="contribution-checkbox" value="{{ $contribution->id }}"></td>
                        <td>
                            <a href="{{ route('admin.members.show', $contribution->member) }}" class="text-decoration-none">
                                <strong>{{ $contribution->member->member_id }}</strong><br>
                                <small>{{ $contribution->member->name }}</small>
                            </a>
                        </td>
                        <td>{{ $contribution->month_name }} {{ $contribution->year }}</td>
                        <td>{{ number_format($contribution->amount, 2) }}</td>
                        <td>{{ number_format($contribution->paid_amount, 2) }}</td>
                        <td class="text-danger">{{ number_format($contribution->due_amount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $contribution->status === 'paid' ? 'success' : ($contribution->status === 'overdue' ? 'danger' : ($contribution->status === 'partial' ? 'warning' : 'secondary')) }}">
                                {{ ucfirst($contribution->status) }}
                            </span>
                        </td>
                        <td>{{ $contribution->due_date?->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.contributions.show', $contribution) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('contributions.edit')
                                @if($contribution->status !== 'paid')
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $contribution->id }}" title="Record Payment">
                                    <i class="bi bi-cash"></i>
                                </button>
                                @endif
                                <a href="{{ route('admin.contributions.edit', $contribution) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('contributions.delete')
                                <form action="{{ route('admin.contributions.destroy', $contribution) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger delete-btn" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>

                    <!-- Payment Modal -->
                    @if($contribution->status !== 'paid')
                    <div class="modal fade" id="paymentModal{{ $contribution->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.contributions.record-payment', $contribution) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Record Payment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Member:</strong> {{ $contribution->member->name }}</p>
                                        <p><strong>Amount Due:</strong> {{ number_format($contribution->remaining_amount, 2) }} SAR</p>
                                        <div class="mb-3">
                                            <label class="form-label">Payment Amount</label>
                                            <input type="number" class="form-control" name="paid_amount" value="{{ $contribution->remaining_amount }}" step="0.01" min="0" max="{{ $contribution->remaining_amount }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Payment Method</label>
                                            <select class="form-select" name="payment_method" required>
                                                <option value="">Select Method</option>
                                                <option value="cash">Cash</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="online">Online Payment</option>
                                                <option value="check">Check</option>
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
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="bi bi-wallet2" style="font-size: 3rem;"></i>
                            <p class="mt-2">No contributions found</p>
                            @can('contributions.create')
                            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#generateModal">Generate Monthly Contributions</a>
                            @endcan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $contributions->withQueryString()->links() }}
    </div>
</div>

<!-- Generate Monthly Modal -->
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.contributions.generate') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Generate Monthly Contributions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This will create monthly contributions for all active members.</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Year</label>
                                <select class="form-select" name="year" required>
                                    @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Month</label>
                                <select class="form-select" name="month" required>
                                    @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Amount per Member (SAR)</label>
                                <input type="number" class="form-control" name="amount" value="{{ $defaultAmount ?? 100 }}" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Due Date</label>
                                <input type="date" class="form-control" name="due_date" value="{{ date('Y-m-d', strtotime('+15 days')) }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.contribution-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
@endpush
