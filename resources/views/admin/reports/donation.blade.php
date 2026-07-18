@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item"><a href="#">Reports</a></li><li class="breadcrumb-item active">Donation</li>')

@section('page_actions')
<a href="{{ route('admin.reports.donation', ['date_from' => $dateFrom, 'date_to' => $dateTo, 'purpose' => request('purpose'), 'pdf' => true]) }}" class="btn btn-outline-danger">
    <i class="bi bi-file-pdf me-2"></i>Export PDF
</a>
@endsection

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Donation Report</h5>
        <p class="text-muted mb-0">{{ date('d M Y', strtotime($dateFrom)) }} - {{ date('d M Y', strtotime($dateTo)) }}</p>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Purpose</label>
                <select name="purpose" class="form-select">
                    <option value="">All</option>
                    <option value="general" {{ request('purpose') == 'general' ? 'selected' : '' }}>General</option>
                    <option value="medical" {{ request('purpose') == 'medical' ? 'selected' : '' }}>Medical</option>
                    <option value="education" {{ request('purpose') == 'education' ? 'selected' : '' }}>Education</option>
                    <option value="emergency" {{ request('purpose') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-success bg-opacity-10">
                    <div class="card-body text-center">
                        <h3 class="text-success">{{ number_format($totalDonations, 2) }} SAR</h3>
                        <p class="mb-0">Total Donations</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-primary bg-opacity-10">
                    <div class="card-body text-center">
                        <h4>{{ $totalCount }}</h4>
                        <p class="mb-0">Total Donors</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info bg-opacity-10">
                    <div class="card-body text-center">
                        <h4>{{ number_format($totalDonations / max($totalCount, 1), 2) }} SAR</h4>
                        <p class="mb-0">Average Donation</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- By Purpose -->
        <h5>By Purpose</h5>
        <table class="table table-sm table-bordered mb-4">
            <thead>
                <tr><th>Purpose</th><th class="text-end">Amount</th></tr>
            </thead>
            <tbody>
                @forelse($byPurpose as $purpose => $amount)
                <tr>
                    <td>{{ ucfirst($purpose) }}</td>
                    <td class="text-end">{{ number_format($amount, 2) }} SAR</td>
                </tr>
                @empty
                <tr><td colspan="2" class="text-center text-muted">No donations</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Donations List -->
        <h5>Donation List</h5>
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Donor</th>
                        <th>Email</th>
                        <th>Amount</th>
                        <th>Purpose</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $donation)
                    <tr>
                        <td>{{ $donation->display_name }}</td>
                        <td>{{ $donation->email ?? '-' }}</td>
                        <td class="text-success">{{ number_format($donation->amount, 2) }} SAR</td>
                        <td>{{ ucfirst($donation->purpose) }}</td>
                        <td>{{ $donation->received_at?->format('d M Y') ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted">No donations found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
