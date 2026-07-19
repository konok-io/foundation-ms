@extends('frontend.layouts.app')

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.activities.index') }}">Activities</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($activity->title, 30) }}</li>
            </ol>
        </nav>

        @if($activity->image)
        <div class="text-center mb-4">
            <img src="{{ asset('storage/' . $activity->image) }}" class="img-fluid rounded" alt="{{ $activity->title }}" style="max-height: 400px;">
        </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <span class="badge bg-primary mb-3">{{ $activity->type }}</span>
                        <h2>{{ $activity->title }}</h2>
                        
                        @if($activity->description)
                        <p class="mt-3">{{ $activity->description }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Activity Details</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Date</strong></td>
                                <td>{{ $activity->start_date->format('d M Y') }}
                                    @if($activity->end_date && $activity->end_date != $activity->start_date)
                                    - {{ $activity->end_date->format('d M Y') }}
                                    @endif
                                </td>
                            </tr>
                            @if($activity->location)
                            <tr>
                                <td><strong>Location</strong></td>
                                <td>{{ $activity->location }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Beneficiaries</strong></td>
                                <td><i class="bi bi-people me-1"></i>{{ $activity->beneficiaries_count }}</td>
                            </tr>
                            <tr>
                                <td><strong>Volunteers</strong></td>
                                <td><i class="bi bi-heart me-1"></i>{{ $activity->volunteers_count }}</td>
                            </tr>
                            <tr>
                                <td><strong>Budget</strong></td>
                                <td>{{ number_format($activity->budget, 2) }} SAR</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
