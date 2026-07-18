@extends('layouts.frontend')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <span class="badge bg-primary mb-2">{{ $event->type }}</span>
                        <h2>{{ $event->title }}</h2>
                        
                        <hr>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1"><strong><i class="bi bi-calendar me-2 text-primary"></i>Date</strong></p>
                                <p class="text-muted">
                                    {{ $event->start_date->format('d M Y') }}
                                    @if($event->end_date && $event->end_date != $event->start_date)
                                    - {{ $event->end_date->format('d M Y') }}
                                    @endif
                                </p>
                            </div>
                            @if($event->start_time)
                            <div class="col-md-6">
                                <p class="mb-1"><strong><i class="bi bi-clock me-2 text-primary"></i>Time</strong></p>
                                <p class="text-muted">
                                    {{ date('h:i A', strtotime($event->start_time)) }}
                                    @if($event->end_time)
                                    - {{ date('h:i A', strtotime($event->end_time)) }}
                                    @endif
                                </p>
                            </div>
                            @endif
                        </div>
                        
                        @if($event->location)
                        <div class="mb-3">
                            <p class="mb-1"><strong><i class="bi bi-geo-alt me-2 text-primary"></i>Location</strong></p>
                            <p class="text-muted">{{ $event->location }}</p>
                        </div>
                        @endif
                        
                        @if($event->description)
                        <div class="mb-3">
                            <p class="mb-1"><strong><i class="bi bi-info-circle me-2 text-primary"></i>Description</strong></p>
                            <p>{{ $event->description }}</p>
                        </div>
                        @endif
                        
                        @if($event->registration_required)
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Registration Required</strong>
                            @if($event->registration_deadline)
                            <br>Registration Deadline: {{ $event->registration_deadline->format('d M Y') }}
                            @endif
                            @if($event->max_attendees)
                            <br>Maximum Attendees: {{ $event->max_attendees }}
                            <br>Spots Left: {{ $event->spotsLeft() }}
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                @if($event->registration_required && $event->registrationOpen())
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-ticket me-2"></i>Register for Event</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        
                        <form method="POST" action="{{ route('public.events.register', $event) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ auth()->user()?->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ auth()->user()?->email }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-lg me-2"></i>Register Now
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="card shadow">
                    <div class="card-body text-center">
                        @if(!$event->registration_required)
                        <i class="bi bi-door-open text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Open Event</h5>
                        <p class="text-muted">No registration required. Walk-ins welcome!</p>
                        @else
                        <i class="bi bi-x-circle text-danger" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Registration Closed</h5>
                        <p class="text-muted">Registration is no longer available for this event.</p>
                        @endif
                    </div>
                </div>
                @endif
                
                <div class="card shadow mt-4">
                    <div class="card-body">
                        <h5><i class="bi bi-people me-2 text-primary"></i>Attendees</h5>
                        <p class="text-muted">{{ $event->registrations->count() }} registered</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
