@extends('member.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Notices</h4>
</div>

@if(count($notices ?? []) > 0)
<div class="row">
    @foreach($notices as $notice)
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="badge bg-{{ $notice->type === 'urgent' ? 'danger' : ($notice->type === 'meeting' ? 'info' : 'primary') }}">
                    {{ ucfirst($notice->type ?? 'General') }}
                </span>
                <small class="text-muted">{{ $notice->created_at->diffForHumans() }}</small>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $notice->title }}</h5>
                <p class="card-text text-muted">{{ Str::limit($notice->content, 150) }}</p>
            </div>
            <div class="card-footer bg-transparent">
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#noticeModal{{ $notice->id }}">
                    Read More
                </button>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="noticeModal{{ $notice->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $notice->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            <i class="bi bi-calendar me-2"></i>{{ $notice->created_at->format('M d, Y') }}
                            @if($notice->notice_date)
                            | <i class="bi bi-calendar-event me-2"></i>Event: {{ $notice->notice_date->format('M d, Y') }}
                            @endif
                        </p>
                        {!! $notice->content !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-megaphone text-muted" style="font-size: 4rem;"></i>
        <h5 class="mt-3 text-muted">No Notices</h5>
        <p class="text-muted">Notices from the foundation will appear here.</p>
    </div>
</div>
@endif
@endsection
