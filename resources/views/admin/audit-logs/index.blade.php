@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb', '<li class="breadcrumb-item active">Audit Logs</li>')

@section('page_actions')
@can('audit-logs.view')
<a href="{{ route('admin.audit-logs.export', request()->query()) }}" class="btn btn-success">
    <i class="bi bi-download me-2"></i>Export CSV
</a>
@endcan
@endsection

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-md-2">
                <select name="action" class="form-select" onchange="this.form.submit()">
                    <option value="">All Actions</option>
                    @foreach(\App\Models\AuditLog::ACTIONS as $key => $action)
                    <option value="{{ $key }}" {{ request('action') == $key ? 'selected' : '' }}>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" onchange="this.form.submit()">
            </div>
            <div class="col-md-2">
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" onchange="this.form.submit()">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Model</th>
                        <th>IP Address</th>
                        <th>Date/Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->user?->name ?? 'System' }}</td>
                        <td>
                            <span class="badge bg-{{ $log->action == 'delete' ? 'danger' : ($log->action == 'create' ? 'success' : ($log->action == 'login' ? 'info' : 'secondary')) }}">
                                {{ $log->action_label }}
                            </span>
                        </td>
                        <td>
                            @if($log->model_type)
                            <small class="text-muted">{{ class_basename($log->model_type) }}</small>
                            @if($log->model_id)
                            <br>#{{ $log->model_id }}
                            @endif
                            @else
                            -
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $log->ip_address ?? '-' }}</small></td>
                        <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.audit-logs.show', $log) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                @can('audit-logs.delete')
                                <form action="{{ route('admin.audit-logs.destroy', $log) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger delete-btn"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No audit logs found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $logs->withQueryString()->links() }}
    </div>
</div>
@endsection
