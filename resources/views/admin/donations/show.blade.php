@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.donations.index') }}">Donations</a></li>
<li class="breadcrumb-item active">Details</li>

@section('page_actions')
<div class="btn-group">
    @can('donations.edit')
    <a href="{{ route('admin.donations.edit', $donation) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-2"></i>Edit
    </a>
    @endcan
    <a href="{{ route('admin.donations.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Donation Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted" style="width: 150px;">Donor Name</td>
                        <td>
                            <strong>{{ $donation->display_name }}</strong>
                            @if($donation->is_anonymous)
                            <span class="badge bg-secondary ms-2">Anonymous</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>{{ $donation->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Phone</td>
                        <td>{{ $donation->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Amount</td>
                        <td><strong class="text-success fs-5">{{ number_format($donation->amount, 2) }} {{ $donation->currency }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Purpose</td>
                        <td>{{ $donation->purpose_label }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Payment Method</td>
                        <td>{{ ucfirst($donation->payment_method) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <span class="badge bg-{{ $donation->status === 'completed' ? 'success' : ($donation->status === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($donation->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
                
                @if($donation->message)
                <hr>
                <h6>Message</h6>
                <p class="mb-0">{{ $donation->message }}</p>
                @endif
            </div>
        </div>

        @if($donation->member)
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Member Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 150px;">Member ID</td>
                        <td>
                            <a href="{{ route('admin.members.show', $donation->member) }}">
                                {{ $donation->member->member_id }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Name</td>
                        <td>{{ $donation->member->name }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Timeline</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Created</td>
                        <td>{{ $donation->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @if($donation->creator)
                    <tr>
                        <td class="text-muted">Created By</td>
                        <td>{{ $donation->creator->name }}</td>
                    </tr>
                    @endif
                    @if($donation->received_at)
                    <tr>
                        <td class="text-muted">Received</td>
                        <td>{{ $donation->received_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @endif
                    @if($donation->receiver)
                    <tr>
                        <td class="text-muted">Received By</td>
                        <td>{{ $donation->receiver->name }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
