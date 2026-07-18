@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.ledger.index') }}">Ledger</a></li>
<li class="breadcrumb-item active">{{ $category->name }}</li>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ $category->name }} - Ledger</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Voucher</th>
                        <th>Description</th>
                        <th class="text-end">{{ $type === 'income' ? 'Credit' : 'Debit' }}</th>
                        <th class="text-end">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php $balance = 0; @endphp
                    @forelse($entries as $entry)
                    <tr>
                        <td>{{ $entry->date->format('d M Y') }}</td>
                        <td><code>{{ $entry->voucher_no }}</code></td>
                        <td>{{ $entry->description ?? '-' }}</td>
                        <td class="text-end">
                            @if($type === 'income')
                                {{ number_format($entry->credit, 2) }}
                                @php $balance += $entry->credit; @endphp
                            @else
                                {{ number_format($entry->debit, 2) }}
                                @php $balance += $entry->debit; @endphp
                            @endif
                        </td>
                        <td class="text-end fw-bold">{{ number_format($balance, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No entries found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-end">Total:</td>
                        <td class="text-end">
                            @if($type === 'income')
                                {{ number_format($totalCredit, 2) }}
                            @else
                                {{ number_format($totalDebit, 2) }}
                            @endif
                        </td>
                        <td class="text-end">{{ number_format($balance, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
