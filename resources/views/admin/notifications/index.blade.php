@extends('admin.layouts.app')

@section('content')
@section('breadcrumb')
<li class="breadcrumb-item active">Notifications</li>
@endsection

@section('page_actions')
@can('notifications.create')
<a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-2"></i>Send Notification
</a>
@endcan
@can('notifications.view')
@if($notifications->where('is_read', false)->count() > 0)
<a href="{{ route('admin.notifications.mark-all-read') }}" class="btn btn-outline-success">
    <i class="bi bi-check-all me-2"></i>Mark All Read
</a>
@endif
@endcan
@endsection

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="type" class="form-select" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    @foreach(\App\Models\MemberNotification::TYPES as $key => $type)
                    <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="is_read" class="form-select" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="0" {{ request('is_read') == '0' ? 'selected' : '' }}>Unread</option>
                    <option value="1" {{ request('is_read') == '1' ? 'selected' : '' }}>Read</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
    <div class="card-body">
        @forelse($notifications as $notification)
        <div class="card mb-2 {{ $notification->is_read ? 'bg-light' : 'border-primary' }}">
            <div class="card-body py-3">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle bg-{{ $notification->is_read ? 'secondary' : 'primary' }} text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-{{ $notification->type == 'payment_received' ? 'cash-stack' : ($notification->type == 'due_reminder' ? 'exclamation-triangle' : 'bell') }}"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1 {{ $notification->is_read ? '' : 'fw-bold' }}">{{ $notification->title }}</h6>
                                <p class="mb-1 text-muted small">{{ $notification->message }}</p>
                                <small class="text-muted">
                                    To: <a href="{{ route('admin.members.show', $notification->member) }}">{{ $notification->member?->name }}</a>
                                    | {{ $notification->created_at->format('d M Y H:i') }}
                                    | <span class="badge bg-{{ $notification->is_read ? 'secondary' : 'primary' }}">{{ $notification->type_label }}</span>
                                </small>
                            </div>
                            <div class="btn-group btn-group-sm">
                                @can('notifications.view')
                                @if(!$notification->is_read)
                                <a href="{{ route('admin.notifications.mark-read', $notification) }}" class="btn btn-outline-success" title="Mark as Read">
                                    <i class="bi bi-check"></i>
                                </a>
                                @endif
                                @endcan
                                @can('notifications.delete')
                                <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger delete-btn"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-bell" style="font-size: 3rem;"></i>
            <p class="mt-2">No notifications found</p>
        </div>
        @endforelse
        {{ $notifications->withQueryString()->links() }}
    </div>
</div>
@endsection
