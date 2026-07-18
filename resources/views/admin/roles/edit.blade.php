@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
<li class="breadcrumb-item active">Edit</li>

@section('page_actions')
<a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-2"></i>Back
</a>
@endsection

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Role</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.roles.update', $role) }}" method="POST" data-loading>
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $role->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" 
                               id="description" name="description" value="{{ old('description', $role->description) }}">
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Permissions <span class="text-danger">*</span></label>
                
                @php
                    $groups = $permissions->filter(function($value, $key) { return !is_null($key); });
                @endphp
                
                @foreach($groups as $groupName => $groupPermissions)
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <input class="form-check-input select-all-group me-2" type="checkbox" data-group="{{ Str::slug($groupName) }}">
                            {{ $groupName }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($groupPermissions as $permission)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox" type="checkbox" 
                                           name="permissions[]" value="{{ $permission->id }}" 
                                           id="perm_{{ $permission->id }}"
                                           data-group="{{ Str::slug($groupName) }}"
                                           {{ in_array($permission->id, old('permissions', $role_permissions)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
                
                @error('permissions')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Update Role
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize group checkboxes
        $('.permission-checkbox').each(function() {
            const group = $(this).data('group');
            const allChecked = $(`.permission-checkbox[data-group="${group}"]`).length === 
                              $(`.permission-checkbox[data-group="${group}"]:checked`).length;
            $(`.select-all-group[data-group="${group}"]`).prop('checked', allChecked);
        });
        
        // Select all in group
        $('.select-all-group').change(function() {
            const group = $(this).data('group');
            const checked = $(this).prop('checked');
            $(`.permission-checkbox[data-group="${group}"]`).prop('checked', checked);
        });
        
        // Check if all permissions in group are checked
        $('.permission-checkbox').change(function() {
            const group = $(this).data('group');
            const allChecked = $(`.permission-checkbox[data-group="${group}"]`).length === 
                              $(`.permission-checkbox[data-group="${group}"]:checked`).length;
            $(`.select-all-group[data-group="${group}"]`).prop('checked', allChecked);
        });
    });
</script>
@endpush
