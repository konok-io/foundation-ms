@extends('admin.layouts.app')

@section('content')
@section('breadcrumb')
<li class="breadcrumb-item active">Blood Donors</li>
@endsection

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-danger bg-opacity-10 border-danger">
            <div class="card-body text-center">
                <h3 class="text-danger">{{ $stats['total'] }}</h3>
                <p class="mb-0">Total Donors</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success bg-opacity-10 border-success">
            <div class="card-body text-center">
                <h3 class="text-success">{{ $stats['available'] }}</h3>
                <p class="mb-0">Available</p>
            </div>
        </div>
    </div>
    @foreach($byBloodGroup as $group => $count)
    <div class="col-md-1-5">
        <div class="card bg-primary bg-opacity-10">
            <div class="card-body text-center">
                <h4 class="text-primary">{{ $count }}</h4>
                <small>{{ $group }}</small>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search name, ID, phone..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="blood_group" class="form-select">
                    <option value="">All Blood Groups</option>
                    @foreach(\App\Models\Member::BLOOD_GROUPS as $key => $group)
                    <option value="{{ $key }}" {{ request('blood_group') == $key ? 'selected' : '' }}>{{ $group }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="availability" class="form-select">
                    <option value="">All Status</option>
                    <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    <option value="temporarily_unavailable" {{ request('availability') == 'temporarily_unavailable' ? 'selected' : '' }}>Temporarily Unavailable</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Blood Group</th>
                        <th>Phone</th>
                        <th>Last Donation</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donors as $donor)
                    <tr>
                        <td><code>{{ $donor->member_id }}</code></td>
                        <td>
                            <a href="{{ route('admin.members.show', $donor) }}">
                                {{ $donor->name }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-danger">{{ $donor->blood_group ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $donor->mobile ?? '-' }}</td>
                        <td>{{ $donor->last_donation_date?->format('d M Y') ?? 'Never' }}</td>
                        <td>
                            @php
                                $badgeClass = match($donor->donation_availability) {
                                    'available' => 'success',
                                    'unavailable' => 'secondary',
                                    'temporarily_unavailable' => 'warning',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }}">
                                {{ ucfirst(str_replace('_', ' ', $donor->donation_availability ?? 'N/A')) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @can('blood_donors.edit')
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $donor->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="{{ route('admin.blood-donors.toggle', $donor) }}" class="btn btn-outline-{{ $donor->is_blood_donor ? 'warning' : 'success' }}">
                                    <i class="bi bi-toggle-on"></i>
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    @can('blood_donors.edit')
                    <div class="modal fade" id="editModal{{ $donor->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.blood-donors.update-availability', $donor) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Availability</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>{{ $donor->name }}</strong> ({{ $donor->blood_group }})</p>
                                        <div class="mb-3">
                                            <label class="form-label">Availability Status</label>
                                            <select name="donation_availability" class="form-select" required>
                                                @foreach(\App\Models\Member::DONATION_AVAILABILITY as $key => $status)
                                                <option value="{{ $key }}" {{ $donor->donation_availability == $key ? 'selected' : '' }}>{{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Last Donation Date</label>
                                            <input type="date" name="last_donation_date" class="form-control" value="{{ $donor->last_donation_date?->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endcan
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No blood donors found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $donors->withQueryString()->links() }}
    </div>
</div>
@endsection
