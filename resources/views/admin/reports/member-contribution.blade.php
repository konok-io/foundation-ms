@extends('admin.layouts.app')

@section('content')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
<li class="breadcrumb-item active">Member Contribution</li>
@endsection

@section('page_actions')
<a href="{{ route('admin.reports.member-contribution', ['year' => $year, 'month' => $month, 'pdf' => true]) }}" class="btn btn-outline-danger">
    <i class="bi bi-file-pdf me-2"></i>Export PDF
</a>
@endsection

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Member Contribution Report</h5>
        <p class="text-muted mb-0">Year: {{ $year }} @if($month) | Month: {{ date('F', mktime(0, 0, 0, $month, 1)) }} @endif</p>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Year</label>
                <select name="year" class="form-select">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Month (optional)</label>
                <select name="month" class="form-select">
                    <option value="">All Months</option>
                    @foreach(range(1, 12) as $m)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card bg-primary bg-opacity-10">
                    <div class="card-body text-center">
                        <h4>{{ $stats['total'] }}</h4>
                        <p class="mb-0">Total</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-success bg-opacity-10">
                    <div class="card-body text-center">
                        <h4>{{ $stats['paid'] }}</h4>
                        <p class="mb-0">Paid</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-warning bg-opacity-10">
                    <div class="card-body text-center">
                        <h4>{{ $stats['partial'] }}</h4>
                        <p class="mb-0">Partial</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-danger bg-opacity-10">
                    <div class="card-body text-center">
                        <h4>{{ $stats['unpaid'] }}</h4>
                        <p class="mb-0">Unpaid</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-success bg-opacity-10">
                    <div class="card-body text-center">
                        <h5>{{ number_format($stats['totalPaid'], 2) }}</h5>
                        <p class="mb-0">Total Paid</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-danger bg-opacity-10">
                    <div class="card-body text-center">
                        <h5>{{ number_format($stats['totalDue'], 2) }}</h5>
                        <p class="mb-0">Total Due</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Period</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contributions as $contribution)
                    <tr>
                        <td>
                            @if($contribution->member)
                            <a href="{{ route('admin.members.show', $contribution->member) }}">
                                {{ $contribution->member->name }}
                            </a>
                            @else
                            N/A
                            @endif
                        </td>
                        <td>{{ $contribution->month_name }} {{ $contribution->year }}</td>
                        <td>{{ number_format($contribution->amount, 2) }}</td>
                        <td>{{ number_format($contribution->paid_amount, 2) }}</td>
                        <td>{{ number_format($contribution->due_amount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $contribution->status === 'paid' ? 'success' : ($contribution->status === 'partial' ? 'warning' : 'danger') }}">
                                {{ ucfirst($contribution->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No contributions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
