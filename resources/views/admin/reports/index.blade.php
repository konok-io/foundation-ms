@extends('admin.layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Financial Reports</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('admin.reports.daily') }}" class="text-decoration-none">
                                <div class="card bg-primary text-white h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-calendar-day fs-1 mb-3"></i>
                                        <h5>Daily Report</h5>
                                        <p class="mb-0 small">View daily collection and expense report</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('admin.reports.monthly') }}" class="text-decoration-none">
                                <div class="card bg-success text-white h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-calendar-month fs-1 mb-3"></i>
                                        <h5>Monthly Report</h5>
                                        <p class="mb-0 small">View monthly collection and expense report</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('admin.reports.yearly') }}" class="text-decoration-none">
                                <div class="card bg-info text-white h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-calendar3 fs-1 mb-3"></i>
                                        <h5>Yearly Report</h5>
                                        <p class="mb-0 small">View yearly collection and expense report</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('admin.reports.member-contribution') }}" class="text-decoration-none">
                                <div class="card bg-warning text-dark h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-people fs-1 mb-3"></i>
                                        <h5>Member Contribution</h5>
                                        <p class="mb-0 small">View member contribution report</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('admin.reports.emergency-fund') }}" class="text-decoration-none">
                                <div class="card bg-danger text-white h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-exclamation-triangle fs-1 mb-3"></i>
                                        <h5>Emergency Fund</h5>
                                        <p class="mb-0 small">View emergency collection report</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('admin.reports.donation') }}" class="text-decoration-none">
                                <div class="card bg-secondary text-white h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-heart fs-1 mb-3"></i>
                                        <h5>Donation Report</h5>
                                        <p class="mb-0 small">View donation collection report</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('admin.reports.outstanding-due') }}" class="text-decoration-none">
                                <div class="card bg-dark text-white h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-clock-history fs-1 mb-3"></i>
                                        <h5>Outstanding Due</h5>
                                        <p class="mb-0 small">View pending due collections</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
