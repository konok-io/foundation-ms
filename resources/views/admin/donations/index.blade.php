@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item"><a href="#">Donations</a></li>')

@section('page_actions')
@can('donations.create')
<a href="{{ route('admin.donations.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-2"></i>Add Donation
</a>
@endcan
@can('donations.export')
<a href="{{ route('admin.donations.export') }}" class="btn btn-outline-success">
    <i class="bi bi-download me-2"></i>Export CSV
</a>
@endcan
@endsection

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-heart text-success" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['total_donations']) }}</h3>
                        <p class="text-muted mb-0">Total Donations</p>
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
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-cash-stack text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['total_amount'], 2) }}</h3>
                        <p class="text-muted mb-0">Total Amount (SAR)</p>
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
                            <i class="bi bi-graph-up text-info" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['average_donation'], 2) }}</h3>
                        <p class="text-muted mb-0">Average (SAR)</p>
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
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-hourglass-split text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($stats['pending_count']) }}</h3>
                        <p class="text-muted mb-0">Pending</p>
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
                <h5 class="mb-0">All Donations</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.donations.index') }}" class="d-flex gap-2 flex-wrap">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        @foreach($statuses as $key => $status)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    <select name="purpose" class="form-select" onchange="this.form.submit()">
                        <option value="">All Purposes</option>
                        @foreach($purposes as $key => $purpose)
                        <option value="{{ $key }}" {{ request('purpose') == $key ? 'selected' : '' }}>{{ $purpose }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" class="form-control" placeholder="Search donor..." value="{{ request('search') }}">
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
                        <th>Donor</th>
                        <th>Amount</th>
                        <th>Purpose</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $donation)
                    <tr>
                        <td>
                            <a href="{{ route('admin.donations.show', $donation) }}" class="text-decoration-none">
                                <strong>{{ $donation->display_name }}</strong>
                            </a>
                            @if($donation->member)
                            <br><small class="text-muted">Member: {{ $donation->member->name }}</small>
                            @endif
                        </td>
                        <td><strong class="text-success">{{ number_format($donation->amount, 2) }}</strong></td>
                        <td>{{ $donation->purpose_label }}</td>
                        <td>{{ ucfirst($donation->payment_method) }}</td>
                        <td>
                            <span class="badge bg-{{ $donation->status === 'completed' ? 'success' : ($donation->status === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($donation->status) }}
                            </span>
                        </td>
                        <td>{{ $donation->received_at?->format('M d, Y') ?? $donation->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.donations.show', $donation) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('donations.edit')
                                <a href="{{ route('admin.donations.edit', $donation) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('donations.delete')
                                <form action="{{ route('admin.donations.destroy', $donation) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger delete-btn" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-heart" style="font-size: 3rem;"></i>
                            <p class="mt-2">No donations found</p>
                            @can('donations.create')
                            <a href="{{ route('admin.donations.create') }}" class="btn btn-primary btn-sm">Add First Donation</a>
                            @endcan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $donations->withQueryString()->links() }}
    </div>
</div>
@endsection
