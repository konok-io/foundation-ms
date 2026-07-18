@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.audit-logs.index') }}">Audit Logs</a></li>
<li class="breadcrumb-item active">Details</li>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Audit Log Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-light" style="width: 30%;">ID</th>
                        <td>{{ $log->id }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">User</th>
                        <td>{{ $log->user?->name ?? 'System' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Action</th>
                        <td>
                            <span class="badge bg-{{ $log->action == 'delete' ? 'danger' : ($log->action == 'create' ? 'success' : 'secondary') }}">
                                {{ $log->action_label }}
                            </span>
                        </td>
                    </tr>
                    @if($log->model_type)
                    <tr>
                        <th class="bg-light">Model</th>
                        <td>
                            <strong>{{ class_basename($log->model_type) }}</strong>
                            @if($log->model_id)
                            <br><small class="text-muted">ID: #{{ $log->model_id }}</small>
                            @endif
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th class="bg-light">IP Address</th>
                        <td>{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">User Agent</th>
                        <td><small>{{ $log->user_agent ?? '-' }}</small></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Date/Time</th>
                        <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                    </tr>
                </table>

                @if($log->old_values || $log->new_values)
                <div class="row mt-4">
                    @if($log->old_values)
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0">Old Values</h6>
                            </div>
                            <div class="card-body">
                                <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($log->new_values)
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">New Values</h6>
                            </div>
                            <div class="card-body">
                                <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
