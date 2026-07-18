@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item"><a href="#">Reports</a></li><li class="breadcrumb-item active">Outstanding Due</li>')

@section('page_actions')
<a href="{{ route('admin.reports.outstanding-due', ['pdf' => true]) }}" class="btn btn-outline-danger">
    <i class="bi bi-file-pdf me-2"></i>Export PDF
</a>
@endsection

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Outstanding Due Report</h5>
        <p class="text-muted mb-0">{{ $memberCount }} members with outstanding dues</p>
    </div>
    <div class="card-body">
        <!-- Summary -->
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <div class="card bg-danger bg-opacity-10 border-danger">
                    <div class="card-body text-center">
                        <h3 class="text-danger">{{ number_format($totalDue, 2) }} SAR</h3>
                        <p class="mb-0">Total Outstanding Due</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th class="text-center">Due Count</th>
                        <th class="text-end">Due Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($membersWithDue as $item)
                    <tr>
                        <td><code>{{ $item['member']->member_id }}</code></td>
                        <td>
                            <a href="{{ route('admin.members.show', $item['member']) }}">
                                {{ $item['member']->name }}
                            </a>
                        </td>
                        <td>
                            <small>{{ $item['member']->phone ?? '-' }}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-danger">{{ $item['due_count'] }}</span>
                        </td>
                        <td class="text-end text-danger fw-bold">
                            {{ number_format($item['due_amount'], 2) }} SAR
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No outstanding dues found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end"><strong>Total</strong></td>
                        <td class="text-end text-danger fw-bold">{{ number_format($totalDue, 2) }} SAR</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
