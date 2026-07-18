@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.members.index') }}">Members</a></li>
<li class="breadcrumb-item active">Create</li>

@section('page_actions')
<a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-2"></i>Back
</a>
@endsection

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add New Member</h5>
                <p class="text-muted mb-0"><small>Member ID: <strong>{{ $nextMemberId }}</strong></small></p>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data" data-loading>
                    @csrf
                    
                    <ul class="nav nav-tabs mb-4" id="memberTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button">
                                <i class="bi bi-person me-1"></i>Personal Info
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button">
                                <i class="bi bi-telephone me-1"></i>Contact
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address" type="button">
                                <i class="bi bi-house me-1"></i>Address
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="professional-tab" data-bs-toggle="tab" data-bs-target="#professional" type="button">
                                <i class="bi bi-briefcase me-1"></i>Professional
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="membership-tab" data-bs-toggle="tab" data-bs-target="#membership" type="button">
                                <i class="bi bi-card-text me-1"></i>Membership
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="memberTabContent">
                        <!-- Personal Info Tab -->
                        <div class="tab-pane fade show active" id="personal">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3 text-center">
                                        <label for="photo" class="form-label d-block">Photo</label>
                                        <div class="photo-preview mb-2">
                                            <img src="{{ asset('images/avatar.png') }}" alt="Preview" id="photoPreview" class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                                        </div>
                                        <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*" onchange="previewPhoto(this)">
                                        <small class="text-muted">Max 2MB, JPG/PNG</small>
                                        @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                                @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name_bn" class="form-label">নাম (Bangla)</label>
                                                <input type="text" class="form-control" id="name_bn" name="name_bn" value="{{ old('name_bn') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="father_name" class="form-label">Father's Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{ old('father_name') }}" required>
                                                @error('father_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="mother_name" class="form-label">Mother's Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('mother_name') is-invalid @enderror" id="mother_name" name="mother_name" value="{{ old('mother_name') }}" required>
                                                @error('mother_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                                @error('date_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                                    <option value="">Select Gender</option>
                                                    @foreach($genders as $key => $gender)
                                                    <option value="{{ $key }}" {{ old('gender') == $key ? 'selected' : '' }}>{{ $gender }}</option>
                                                    @endforeach
                                                </select>
                                                @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="blood_group" class="form-label">Blood Group</label>
                                                <select class="form-select" id="blood_group" name="blood_group">
                                                    <option value="">Select</option>
                                                    @foreach($bloodGroups as $key => $group)
                                                    <option value="{{ $key }}" {{ old('blood_group') == $key ? 'selected' : '' }}>{{ $group }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Tab -->
                        <div class="tab-pane fade" id="contact">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile') }}" required>
                                        @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h6>Emergency Contact</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="emergency_contact_name" class="form-label">Contact Name</label>
                                        <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="emergency_contact_phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="emergency_contact_relation" class="form-label">Relation</label>
                                        <input type="text" class="form-control" id="emergency_contact_relation" name="emergency_contact_relation" value="{{ old('emergency_contact_relation') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Address Tab -->
                        <div class="tab-pane fade" id="address">
                            <h6>Present Address <span class="text-danger">*</span></h6>
                            <div class="mb-3">
                                <textarea class="form-control @error('present_address') is-invalid @enderror" id="present_address" name="present_address" rows="3" required>{{ old('present_address') }}</textarea>
                                @error('present_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" id="present_address_bn" name="present_address_bn" rows="2" placeholder="বর্তমান ঠিকানা (Bangla)">{{ old('present_address_bn') }}</textarea>
                            </div>
                            
                            <hr>
                            <h6>Permanent Address</h6>
                            <div class="mb-3">
                                <textarea class="form-control" id="permanent_address" name="permanent_address" rows="3">{{ old('permanent_address') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" id="permanent_address_bn" name="permanent_address_bn" rows="2" placeholder="স্থায়ী ঠিকানা (Bangla)">{{ old('permanent_address_bn') }}</textarea>
                            </div>
                        </div>
                        
                        <!-- Professional Tab -->
                        <div class="tab-pane fade" id="professional">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="occupation" class="form-label">Occupation</label>
                                        <input type="text" class="form-control" id="occupation" name="occupation" value="{{ old('occupation') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="designation" class="form-label">Designation</label>
                                        <input type="text" class="form-control" id="designation" name="designation" value="{{ old('designation') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company/Organization Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}">
                            </div>
                            
                            <hr>
                            <h6>ID Numbers</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="national_id" class="form-label">National ID</label>
                                        <input type="text" class="form-control" id="national_id" name="national_id" value="{{ old('national_id') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="passport_number" class="form-label">Passport Number</label>
                                        <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ old('passport_number') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="iqama_number" class="form-label">Iqama Number</label>
                                        <input type="text" class="form-control" id="iqama_number" name="iqama_number" value="{{ old('iqama_number') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Membership Tab -->
                        <div class="tab-pane fade" id="membership">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="join_date" class="form-label">Join Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('join_date') is-invalid @enderror" id="join_date" name="join_date" value="{{ old('join_date', date('Y-m-d')) }}" required>
                                        @error('join_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="member_type" class="form-label">Member Type</label>
                                        <select class="form-select" id="member_type" name="member_type">
                                            @foreach($memberTypes as $key => $type)
                                            <option value="{{ $key }}" {{ old('member_type', 'general') == $key ? 'selected' : '' }}>{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="position" class="form-label">Position</label>
                                        <select class="form-select" id="position" name="position">
                                            @foreach($positions as $key => $pos)
                                            <option value="{{ $key }}" {{ old('position', 'member') == $key ? 'selected' : '' }}>{{ $pos }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="referrer_member_id" class="form-label">Referrer Member ID</label>
                                        <input type="text" class="form-control" id="referrer_member_id" name="referrer_member_id" value="{{ old('referrer_member_id') }}" placeholder="FMS-2026-0001">
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            <h6>Nominee Information</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="nominee_name" class="form-label">Nominee Name</label>
                                        <input type="text" class="form-control" id="nominee_name" name="nominee_name" value="{{ old('nominee_name') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="nominee_relation" class="form-label">Relation</label>
                                        <input type="text" class="form-control" id="nominee_relation" name="nominee_relation" value="{{ old('nominee_relation') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="nominee_phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="nominee_phone" name="nominee_phone" value="{{ old('nominee_phone') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">
                                        Active (Member is active and can access the system)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Create Member
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
