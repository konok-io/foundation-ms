@extends('admin.auth.master')

@section('title', 'Forgot Password')

@section('content')
<div class="auth-card">
    <div class="card shadow-lg">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="bi bi-lock-fill text-white fs-1"></i>
                <h3 class="mt-3 text-white">Forgot Password?</h3>
                <p class="text-white-50">Enter your email to reset your password</p>
            </div>

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label text-white">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control form-control-lg" 
                               placeholder="Enter your email" value="{{ old('email') }}" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-light btn-lg w-100">
                    <i class="bi bi-send me-2"></i> Send Reset Link
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-white">
                    <i class="bi bi-arrow-left me-1"></i> Back to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
