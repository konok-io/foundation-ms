@extends('admin.layouts.app')

@section('content')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
<li class="breadcrumb-item active">Emergency Fund</li>
@endsection

@section('page_actions')
<a href="{{ route('admin.reports.emergency-fund', ['pdf' => true]) }}" class="btn btn-outline-danger">
    <i class="bi bi-file-pdf me-2"></i>Export PDF
</a>
@endsection

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Emergency Fund Report</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Collection</th>
                        <th>Amount/Member</th>
                        <th>Total Expected</th>
                        <th>Collected</th>
                        <th>Due</th>
                        <th>Paid</th>
                        <th>Unpaid</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collections as $collection)
                    @php $stat = $stats[$collection->id]; @endphp
                    <tr>
                        <td>
                            <strong>{{ $collection->title }}</strong>
                            <br><small class="text-muted">{{ $collection->created_at->format('d M Y') }}</small>
                        </td>
                        <td>{{ number_format($collection->amount, 2) }} SAR</td>
                        <td>{{ number_format($stat['total'], 2) }} SAR</td>
                        <td class="text-success">{{ number_format($stat['collected'], 2) }} SAR</td>
                        <td class="text-danger">{{ number_format($stat['due'], 2) }} SAR</td>
                        <td>{{ $stat['paid_count'] }}</td>
                        <td>{{ $stat['unpaid_count'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No emergency collections found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
