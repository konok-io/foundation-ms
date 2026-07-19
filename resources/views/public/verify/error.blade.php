@extends('frontend.layouts.premium')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center border-danger">
                    <div class="card-body py-5">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 5rem;"></i>
                        <h2 class="mt-4 text-danger">Verification Failed</h2>
                        <p class="text-muted mt-3">{{ $message }}</p>
                        <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-house me-2"></i>Go Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
