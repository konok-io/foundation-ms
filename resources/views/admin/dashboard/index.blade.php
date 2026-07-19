@extends('admin.layouts.premium')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const collectionsCtx = document.getElementById('collectionsChart');
    if (collectionsCtx) {
        const collectionsData = @json($charts['monthly_collections']);
        new Chart(collectionsCtx, {
            type: 'bar',
            data: {
                labels: collectionsData.map(d => d.month),
                datasets: [
                    { label: 'Monthly Due', data: collectionsData.map(d => d.monthly_due), backgroundColor: 'rgba(14, 116, 144, 0.8)', borderRadius: 8 },
                    { label: 'Emergency', data: collectionsData.map(d => d.emergency), backgroundColor: 'rgba(239, 68, 68, 0.8)', borderRadius: 8 },
                    { label: 'Donations', data: collectionsData.map(d => d.donations), backgroundColor: 'rgba(22, 163, 74, 0.8)', borderRadius: 8 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } } }
        });
    }

    const memberGrowthCtx = document.getElementById('memberGrowthChart');
    if (memberGrowthCtx) {
        const memberGrowthData = @json($charts['member_growth']);
        new Chart(memberGrowthCtx, {
            type: 'line',
            data: {
                labels: memberGrowthData.map(d => d.month),
                datasets: [{ label: 'Total Members', data: memberGrowthData.map(d => d.count), borderColor: '#0E7490', backgroundColor: 'rgba(14, 116, 144, 0.1)', fill: true, tension: 0.4, borderWidth: 3 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } } }
        });
    }

    const bloodGroupCtx = document.getElementById('bloodGroupChart');
    if (bloodGroupCtx) {
        const bloodData = @json($bloodGroupStats);
        const bloodLabels = Object.keys(bloodData);
        const bloodValues = Object.values(bloodData);
        new Chart(bloodGroupCtx, {
            type: 'doughnut',
            data: {
                labels: bloodLabels,
                datasets: [{ data: bloodValues, backgroundColor: ['#0E7490', '#16A34A', '#F59E0B', '#EF4444', '#3B82F6', '#8B5CF6', '#EC4899', '#6B7280'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
        });
    }
});
</script>
@endpush

@section('content')
<!-- Welcome Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="premium-card p-4" style="background: linear-gradient(135deg, #0E7490 0%, #16A34A 100%);">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="text-white mb-1">Welcome back, {{ Auth::user()->name }}!</h4>
                    <p class="text-white-50 mb-0">Here's what's happening with your foundation today.</p>
                </div>
                <div class="text-white-50">
                    <i class="bi bi-building" style="font-size: 4rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card primary" data-aos="fade-up" data-aos-delay="0">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-icon"><i class="bi bi-people"></i></div>
                    <div class="stat-value">{{ number_format($stats['total_members']) }}</div>
                    <div class="stat-label">Total Members</div>
                    <span class="stat-change up"><i class="bi bi-arrow-up"></i> {{ $stats['new_members_this_month'] }} this month</span>
                </div>
                <a href="{{ route('admin.members.index') }}" class="stat-link"><i class="bi bi-arrow-right-circle fs-5"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card success" data-aos="fade-up" data-aos-delay="100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
                    <div class="stat-value">{{ number_format($stats['total_monthly_collected'], 0) }}</div>
                    <div class="stat-label">SAR Collected This Month</div>
                    <span class="stat-change"><i class="bi bi-wallet2"></i> Due: {{ number_format($stats['total_monthly_due'], 0) }} SAR</span>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="stat-link"><i class="bi bi-arrow-right-circle fs-5"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card warning" data-aos="fade-up" data-aos-delay="200">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-icon"><i class="bi bi-heart"></i></div>
                    <div class="stat-value">{{ number_format($stats['total_donations'], 0) }}</div>
                    <div class="stat-label">SAR Donations This Year</div>
                    <span class="stat-change up"><i class="bi bi-people"></i> {{ $stats['total_donors'] }} donors</span>
                </div>
                <a href="{{ route('admin.donations.index') }}" class="stat-link"><i class="bi bi-arrow-right-circle fs-5"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card danger" data-aos="fade-up" data-aos-delay="300">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-icon"><i class="bi bi-activity"></i></div>
                    <div class="stat-value">{{ number_format($stats['total_activities']) }}</div>
                    <div class="stat-label">Total Activities</div>
                    <span class="stat-change up"><i class="bi bi-calendar-check"></i> {{ $stats['completed_activities'] }} completed</span>
                </div>
                <a href="{{ route('admin.activities.index') }}" class="stat-link"><i class="bi bi-arrow-right-circle fs-5"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="premium-card" data-aos="fade-up">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Collections Overview (Last 12 Months)</h5>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-primary">View Reports</a>
            </div>
            <div class="card-body">
                <div style="height: 300px;"><canvas id="collectionsChart"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="premium-card" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up-arrow me-2"></i>Member Growth</h5>
            </div>
            <div class="card-body">
                <div style="height: 150px;"><canvas id="memberGrowthChart"></canvas></div>
            </div>
        </div>
        <div class="premium-card mt-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-droplet me-2"></i>Blood Group Distribution</h5>
            </div>
            <div class="card-body">
                <div style="height: 150px;"><canvas id="bloodGroupChart"></canvas></div>
            </div>
        </div>
    </div>
</div>

<!-- Activity & Events Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="premium-card" data-aos="fade-up">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Activity</h5>
            </div>
            <div class="card-body p-0">
                @if(count($recentActivity) > 0)
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivity as $activity)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-{{ $activity['icon'] }} text-{{ $activity['color'] }}"></i>
                                            </div>
                                        </div>
                                        <div>{{ $activity['message'] }}</div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $activity['time'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                    <h5>No Recent Activity</h5>
                    <p>Activity will appear here when members make payments or donations.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="premium-card" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Upcoming Events</h5>
                <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-link p-0">View All</a>
            </div>
            <div class="card-body">
                @if($upcomingEvents->count() > 0)
                @foreach($upcomingEvents as $event)
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="flex-shrink-0">
                        <div class="bg-gradient text-white rounded p-2 text-center" style="min-width: 55px; background: linear-gradient(135deg, #0E7490, #16A34A);">
                            <div class="small">{{ $event->start_date->format('M') }}</div>
                            <div class="fw-bold fs-5">{{ $event->start_date->format('d') }}</div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">{{ Str::limit($event->title, 30) }}</h6>
                        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $event->location ?? 'TBD' }}</small>
                    </div>
                </div>
                @endforeach
                @else
                <div class="empty-state py-4">
                    <div class="empty-state-icon"><i class="bi bi-calendar-x"></i></div>
                    <h5>No Upcoming Events</h5>
                    <p>Create events to keep members engaged.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Recent Members -->
<div class="row g-4">
    <div class="col-xl-4">
        <div class="premium-card" data-aos="fade-up">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    @can('members.create')
                    <a href="{{ route('admin.members.create') }}" class="btn btn-primary text-white">
                        <i class="bi bi-person-plus me-2"></i>Add New Member
                    </a>
                    @endcan
                    @can('payments.create')
                    <a href="{{ route('admin.payments.create') }}" class="btn btn-success text-white">
                        <i class="bi bi-cash me-2"></i>Record Payment
                    </a>
                    @endcan
                    <a href="{{ route('admin.donations.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-heart me-2"></i>View Donations
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>Generate Report
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="premium-card" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>Recent Members</h5>
                <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-link p-0">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMembers as $member)
                            <tr>
                                <td><a href="{{ route('admin.members.show', $member) }}" class="text-primary fw-semibold">{{ $member->member_id }}</a></td>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->phone ?? 'N/A' }}</td>
                                <td>
                                    @if($member->status == 'active')
                                    <span class="badge badge-success">Active</span>
                                    @else
                                    <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $member->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No members found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
