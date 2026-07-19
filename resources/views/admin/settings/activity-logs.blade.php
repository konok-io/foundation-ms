@extends('admin.layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Activity Logs</h5>
                <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Settings
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Description</th>
                            <th>User</th>
                            <th>Subject</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                        <tr>
                            <td>{{ $activity->id }}</td>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->causer?->name ?? 'System' }}</td>
                            <td>
                                @if($activity->subject)
                                    {{ class_basename($activity->subject) }} #{{ $activity->subject_id }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $activity->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No activities found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection
