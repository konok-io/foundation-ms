@extends('frontend.layouts.premium')

@section('content')
<!-- Page Header -->
<section class="py-5 text-center" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
    <div class="container py-5">
        <h1 class="display-4 fw-bold text-white mb-3">Our Members</h1>
        <p class="lead text-white opacity-75">Meet the dedicated members of Foundation MS</p>
    </div>
</section>

<!-- Members Section -->
<section class="py-5">
    <div class="container">
        <!-- Filter Form -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body p-4">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or ID..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="blood_group" class="form-select">
                            <option value="">All Blood Groups</option>
                            @foreach($bloodGroups as $bg)
                                <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>Search
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('public.members.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Members Grid -->
        @if($members->count() > 0)
            <div class="row g-4">
                @foreach($members as $member)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm member-card">
                        <div class="card-body text-center p-4">
                            <div class="avatar-wrapper mb-3">
                                @if($member->photo)
                                    <img src="{{ asset('storage/members/' . $member->photo) }}" alt="{{ $member->name }}" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); margin: 0 auto;">
                                        <span class="text-white display-6">{{ substr($member->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <h5 class="fw-bold mb-1">{{ $member->name }}</h5>
                            <p class="text-muted small mb-2">{{ $member->member_id }}</p>
                            <span class="badge bg-danger mb-3">
                                <i class="bi bi-droplet-fill me-1"></i>{{ $member->blood_group ?? 'N/A' }}
                            </span>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('public.members.show', $member->id) }}" class="btn btn-sm btn-outline-primary">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $members->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">No members found</h3>
                <p class="text-muted">Try adjusting your search criteria</p>
            </div>
        @endif
    </div>
</section>

@push('styles')
<style>
.member-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}
.member-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}
.avatar-placeholder {
    margin: 0 auto;
}
</style>
@endpush
@endsection
