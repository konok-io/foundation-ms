@extends('admin.layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Collections Chart
    const collectionsCtx = document.getElementById('collectionsChart');
    if (collectionsCtx) {
        const collectionsData = @json($charts['monthly_collections']);
        new Chart(collectionsCtx, {
            type: 'bar',
            data: {
                labels: collectionsData.map(d => d.month),
                datasets: [
                    {
                        label: 'Monthly Due',
                        data: collectionsData.map(d => d.monthly_due),
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    },
                    {
                        label: 'Emergency',
                        data: collectionsData.map(d => d.emergency),
                        backgroundColor: 'rgba(255, 99, 132, 0.8)',
                    },
                    {
                        label: 'Donations',
                        data: collectionsData.map(d => d.donations),
                        backgroundColor: 'rgba(75, 192, 192, 0.8)',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Member Growth Chart
    const memberGrowthCtx = document.getElementById('memberGrowthChart');
    if (memberGrowthCtx) {
        const memberGrowthData = @json($charts['member_growth']);
        new Chart(memberGrowthCtx, {
            type: 'line',
            data: {
                labels: memberGrowthData.map(d => d.month),
                datasets: [{
                    label: 'Total Members',
                    data: memberGrowthData.map(d => d.count),
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Blood Group Distribution
    const bloodGroupCtx = document.getElementById('bloodGroupChart');
    if (bloodGroupCtx) {
        const bloodData = @json($bloodGroupStats);
        const bloodLabels = Object.keys(bloodData);
        const bloodValues = Object.values(bloodData);
        
        new Chart(bloodGroupCtx, {
            type: 'doughnut',
            data: {
                labels: bloodLabels,
                datasets: [{
                    data: bloodValues,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(255, 159, 64, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(201, 203, 207, 0.8)',
                        'rgba(255, 87, 34, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });
    }
});
</script>
@endpush

@section('content')
<div class="row">
    <!-- Member Stats -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Members</div>
                        <div class="h5 mb-0 fw-bold">{{ number_format($stats['total_members']) }}</div>
                        <small class="text-success text-xs">
                            <i class="bi bi-arrow-up"></i> {{ $stats['new_members_this_month'] }} this month
                        </small>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('admin.members.index') }}" class="text-primary text-decoration-none small">
                    View Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Monthly Collected</div>
                        <div class="h5 mb-0 fw-bold">{{ number_format($stats['total_monthly_collected'], 0) }} SAR</div>
                        <small class="text-muted text-xs">
                            Due: {{ number_format($stats['total_monthly_due'], 0) }} SAR
                        </small>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-cash-stack text-success" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('admin.payments.index') }}" class="text-success text-decoration-none small">
                    View Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Total Donations</div>
                        <div class="h5 mb-0 fw-bold">{{ number_format($stats['total_donations'], 0) }} SAR</div>
                        <small class="text-info text-xs">
                            <i class="bi bi-people"></i> {{ $stats['total_donors'] }} donors this year
                        </small>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-heart text-info" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('admin.donations.index') }}" class="text-info text-decoration-none small">
                    View Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Activities</div>
                        <div class="h5 mb-0 fw-bold">{{ $stats['completed_activities'] }} / {{ $stats['total_activities'] }}</div>
                        <small class="text-warning text-xs">
                            <i class="bi bi-calendar-event"></i> {{ $stats['upcoming_events'] }} upcoming events
                        </small>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-activity text-warning" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('admin.activities.index') }}" class="text-warning text-decoration-none small">
                    View Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Collections Chart -->
    <div class="col-xl-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Collections Overview (Last 12 Months)</h6>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="collectionsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Member Growth Chart -->
    <div class="col-xl-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Member Growth</h6>
            </div>
            <div class="card-body">
                <div style="height: 150px;">
                    <canvas id="memberGrowthChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Blood Group Distribution</h6>
            </div>
            <div class="card-body">
                <div style="height: 150px;">
                    <canvas id="bloodGroupChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activity -->
    <div class="col-xl-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
            </div>
            <div class="card-body">
                @if(count($recentActivity) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
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
                                    <i class="bi bi-{{ $activity['icon'] }} text-{{ $activity['color'] }} me-2"></i>
                                    {{ $activity['message'] }}
                                </td>
                                <td class="text-muted small">{{ $activity['time'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center py-4">No recent activity</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Upcoming Events & Quick Actions -->
    <div class="col-xl-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Upcoming Events</h6>
            </div>
            <div class="card-body">
                @if($upcomingEvents->count() > 0)
                @foreach($upcomingEvents as $event)
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded p-2 text-center" style="min-width: 50px;">
                            <div class="small">{{ $event->start_date->format('M') }}</div>
                            <div class="fw-bold">{{ $event->start_date->format('d') }}</div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">{{ Str::limit($event->title, 30) }}</h6>
                        <small class="text-muted">{{ $event->location ?? 'TBD' }}</small>
                    </div>
                </div>
                @endforeach
                @else
                <p class="text-muted text-center py-2">No upcoming events</p>
                @endif
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @can('members.create')
                    <a href="{{ route('admin.members.create') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-person-plus me-2"></i>Add Member
                    </a>
                    @endcan
                    @can('payments.create')
                    <a href="{{ route('admin.payments.create') }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-cash me-2"></i>Record Payment
                    </a>
                    @endcan
                    @can('donations.view')
                    <a href="{{ route('admin.donations.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-heart me-2"></i>View Donations
                    </a>
                    @endcan
                    @can('reports.view')
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>View Reports
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Members -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Recent Members</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMembers as $member)
                            <tr>
                                <td><a href="{{ route('admin.members.show', $member) }}">{{ $member->member_id }}</a></td>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->phone ?? 'N/A' }}</td>
                                <td>
                                    @if($member->status)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-secondary">Inactive</span>
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
