@extends('member.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Emergency Collections</h4>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Emergency Fund Collections</h6>
    </div>
    <div class="card-body">
        @if(count($collections ?? []) > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Paid Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($collections as $collection)
                    <tr>
                        <td>{{ $collection->title }}</td>
                        <td>{{ Str::limit($collection->description, 50) }}</td>
                        <td>{{ number_format($collection->amount, 2) }} {{ config('app.currency', 'SAR') }}</td>
                        <td>
                            <span class="badge bg-{{ $collection->pivot?->status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($collection->pivot?->status ?? 'unpaid') }}
                            </span>
                        </td>
                        <td>{{ $collection->paid_at?->format('M d, Y') ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-exclamation-triangle text-muted" style="font-size: 4rem;"></i>
            <h5 class="mt-3 text-muted">No Emergency Collections</h5>
            <p class="text-muted">Emergency fund collection history will appear here.</p>
        </div>
        @endif
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h6><i class="bi bi-info-circle me-2"></i>About Emergency Collections</h6>
        <p class="text-muted mb-0">
            Emergency collections are special fund-raising initiatives for urgent causes such as 
            medical emergencies, natural disaster relief, funeral support, etc. As a member, 
            you may be asked to contribute to these collections as they arise.
        </p>
    </div>
</div>
@endsection
