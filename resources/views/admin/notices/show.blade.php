@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.notices.index') }}">Notices</a></li>
<li class="breadcrumb-item active">{{ $notice->title }}</li>

@section('page_actions')
<div class="btn-group">
    @can('notices.edit')
    <a href="{{ route('admin.notices.edit', $notice) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-2"></i>Edit
    </a>
    @endcan
    <a href="{{ route('admin.notices.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>
@endsection

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $notice->title }}</h5>
                    @php
                        $priorityClass = match($notice->priority) {
                            'urgent' => 'danger',
                            'high' => 'warning',
                            'normal' => 'info',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $priorityClass }}">{{ ucfirst($notice->priority) }} Priority</span>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-4">
                    <tr>
                        <th class="bg-light" style="width: 30%;">Type</th>
                        <td>{{ $notice->type }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Priority</th>
                        <td>{{ ucfirst($notice->priority) }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Publish Date</th>
                        <td>{{ $notice->publish_date?->format('d M Y') ?? 'Immediate' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Expire Date</th>
                        <td>{{ $notice->expire_date?->format('d M Y') ?? 'Never' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Status</th>
                        <td>
                            @if($notice->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">Created</th>
                        <td>{{ $notice->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
                
                @if($notice->content)
                <h6>Content</h6>
                <p>{{ $notice->content }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
