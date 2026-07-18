@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.receipts.index') }}">Receipts</a></li>
<li class="breadcrumb-item active">Details</li>

@section('page_actions')
<div class="btn-group">
    @can('receipts.download')
    <a href="{{ route('admin.receipts.download', $receipt) }}" class="btn btn-success">
        <i class="bi bi-download me-2"></i>Download PDF
    </a>
    <a href="{{ route('admin.receipts.print', $receipt) }}" target="_blank" class="btn btn-info">
        <i class="bi bi-printer me-2"></i>Print
    </a>
    @endcan
    @can('receipts.email')
    <a href="{{ route('admin.receipts.email', $receipt) }}" class="btn btn-warning">
        <i class="bi bi-envelope me-2"></i>Send Email
    </a>
    @endcan
    <a href="{{ route('admin.receipts.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Receipt Information</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h2 class="text-primary">{{ $receipt->receipt_no }}</h2>
                    <span class="badge bg-{{ $receipt->is_printed ? 'success' : 'secondary' }}">
                        {{ $receipt->is_printed ? 'Printed' : 'Not Printed' }}
                    </span>
                    <span class="badge bg-{{ $receipt->is_emailed ? 'info' : 'secondary' }}">
                        {{ $receipt->is_emailed ? 'Emailed' : 'Not Emailed' }}
                    </span>
                </div>

                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted" style="width: 150px;">Type</td>
                        <td>{{ $types[$receipt->type] ?? $receipt->type }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Amount</td>
                        <td><strong class="text-success fs-5">{{ number_format($receipt->amount, 2) }} {{ $receipt->currency }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Payment Method</td>
                        <td>{{ ucfirst($receipt->payment_method) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Paid Date</td>
                        <td>{{ $receipt->paid_at?->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Purpose</td>
                        <td>{{ $receipt->purpose ?? 'N/A' }}</td>
                    </tr>
                    @if($receipt->description)
                    <tr>
                        <td class="text-muted">Description</td>
                        <td>{{ $receipt->description }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        @if($receipt->member)
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Member Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 150px;">Member ID</td>
                        <td>
                            <a href="{{ route('admin.members.show', $receipt->member) }}">
                                {{ $receipt->member->member_id }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Name</td>
                        <td>{{ $receipt->member->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>{{ $receipt->member->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Phone</td>
                        <td>{{ $receipt->member->mobile ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Audit Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 150px;">Created</td>
                        <td>{{ $receipt->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @if($receipt->creator)
                    <tr>
                        <td class="text-muted">Created By</td>
                        <td>{{ $receipt->creator->name }}</td>
                    </tr>
                    @endif
                    @if($receipt->printed_at)
                    <tr>
                        <td class="text-muted">Printed At</td>
                        <td>{{ $receipt->printed_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @endif
                    @if($receipt->emailed_at)
                    <tr>
                        <td class="text-muted">Emailed At</td>
                        <td>{{ $receipt->emailed_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
