@extends('member.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">My Profile</h4>
    <span class="badge bg-primary">{{ $member->member_id }}</span>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Profile Card -->
        <div class="card mb-4">
            <div class="card-body profile-card">
                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="rounded-circle mb-3">
                <h5>{{ $member->name }}</h5>
                @if($member->name_bn)
                <p class="text-muted mb-1">{{ $member->name_bn }}</p>
                @endif
                <span class="badge bg-primary mb-2">{{ $member->member_id }}</span>
                <p class="mb-1">
                    <span class="badge bg-{{ $member->status ? 'success' : 'warning' }}">
                        {{ $member->status ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="badge bg-info">
                        {{ $memberTypes[$member->member_type] ?? 'General' }}
                    </span>
                </p>
                <p class="text-muted small mb-0">
                    {{ $positions[$member->position] ?? 'Member' }}
                </p>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('member.card') }}" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-credit-card me-2"></i>View ID Card
                </a>
                <a href="{{ route('member.change-password') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-key me-2"></i>Change Password
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Edit Profile Form -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Edit Profile</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" data-loading>
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-center">
                            <label class="form-label d-block">Photo</label>
                            <img src="{{ $member->avatar_url }}" alt="" id="photoPreview" class="rounded mb-2" width="100" height="100" style="object-fit: cover;">
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*" onchange="previewPhoto(this)">
                            <small class="text-muted">Max 2MB, JPG/PNG</small>
                            @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $member->name) }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name_bn" class="form-label">নাম (Bangla)</label>
                                        <input type="text" class="form-control" id="name_bn" name="name_bn" value="{{ old('name_bn', $member->name_bn) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile', $member->mobile) }}" required>
                                        @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $member->email) }}">
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="mb-3">Address Information</h6>
                    
                    <div class="mb-3">
                        <label for="present_address" class="form-label">Present Address</label>
                        <textarea class="form-control" id="present_address" name="present_address" rows="2">{{ old('present_address', $member->present_address) }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="permanent_address" class="form-label">Permanent Address</label>
                        <textarea class="form-control" id="permanent_address" name="permanent_address" rows="2">{{ old('permanent_address', $member->permanent_address) }}</textarea>
                    </div>
                    
                    <hr>
                    <h6 class="mb-3">Emergency Contact</h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="emergency_contact_name" class="form-label">Contact Name</label>
                                <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $member->emergency_contact_name) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="emergency_contact_phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $member->emergency_contact_phone) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="emergency_contact_relation" class="form-label">Relation</label>
                                <input type="text" class="form-control" id="emergency_contact_relation" name="emergency_contact_relation" value="{{ old('emergency_contact_relation', $member->emergency_contact_relation) }}">
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="mb-3">Nominee Information</h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="nominee_name" class="form-label">Nominee Name</label>
                                <input type="text" class="form-control" id="nominee_name" name="nominee_name" value="{{ old('nominee_name', $member->nominee_name) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="nominee_relation" class="form-label">Relation</label>
                                <input type="text" class="form-control" id="nominee_relation" name="nominee_relation" value="{{ old('nominee_relation', $member->nominee_relation) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="nominee_phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="nominee_phone" name="nominee_phone" value="{{ old('nominee_phone', $member->nominee_phone) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#photoPreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
