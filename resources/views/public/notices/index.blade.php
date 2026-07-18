@extends('layouts.frontend')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2><i class="bi bi-megaphone me-2"></i>Notices & Announcements</h2>
            <p class="text-muted">Stay updated with the latest notices from our foundation</p>
        </div>

        @if($notices->count() > 0)
        <div class="row">
            <div class="col-md-8 mx-auto">
                @foreach($notices as $notice)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-{{ $notice->priority === 'urgent' ? 'danger' : ($notice->priority === 'high' ? 'warning' : 'light') }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-{{ $notice->type === 'emergency' ? 'danger' : 'secondary' }} me-2">{{ $notice->type }}</span>
                                <strong>{{ $notice->title }}</strong>
                            </div>
                            @if($notice->priority === 'urgent')
                            <span class="badge bg-danger">URGENT</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if($notice->content)
                        <p>{{ $notice->content }}</p>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center text-muted">
                            <small>
                                <i class="bi bi-calendar me-1"></i>
                                Published: {{ $notice->publish_date?->format('d M Y') ?? 'N/A' }}
                            </small>
                            @if($notice->expire_date)
                            <small>
                                <i class="bi bi-clock me-1"></i>
                                Expires: {{ $notice->expire_date->format('d M Y') }}
                            </small>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $notices->links() }}
        </div>
        @else
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-2"></i>
            No current notices at the moment.
        </div>
        @endif
    </div>
</section>
@endsection
