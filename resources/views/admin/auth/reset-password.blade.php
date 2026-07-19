@extends('admin.auth.master')

@section('title', 'Reset Password')

@section('content')
<div class="auth-card">
    <div class="card shadow-lg">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="bi bi-shield-lock-fill text-white fs-1"></i>
                <h3 class="mt-3 text-white">Reset Password</h3>
                <p class="text-white-50">Enter your new password</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-4">
                    <label class="form-label text-white">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg" 
                           value="{{ old('email', $email ?? '') }}" required readonly>
                </div>

                <div class="mb-4">
                    <label class="form-label text-white">New Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" 
                           placeholder="Enter new password" required>
                </div>

                <div class="mb-4">
                    <label class="form-label text-white">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-lg" 
                           placeholder="Confirm new password" required>
                </div>

                <button type="submit" class="btn btn-light btn-lg w-100">
                    <i class="bi bi-check-circle me-2"></i> Reset Password
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
