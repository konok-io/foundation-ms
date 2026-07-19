@extends('frontend.layouts.premium')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="text-center mb-4">
                            <i class="bi bi-droplet-fill text-danger me-2"></i>
                            Blood Donor Search
                        </h3>
                        
                        <form method="GET" action="{{ route('blood-donors.search') }}" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Select Blood Group</label>
                                    <select name="blood_group" class="form-select form-select-lg" required>
                                        <option value="">Choose...</option>
                                        @foreach(\App\Models\Member::BLOOD_GROUPS as $key => $group)
                                        <option value="{{ $key }}" {{ $bloodGroup == $key ? 'selected' : '' }}>
                                            {{ $group }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-danger btn-lg w-100">
                                        <i class="bi bi-search me-2"></i>Search
                                    </button>
                                </div>
                            </div>
                        </form>

                        @if($bloodGroup)
                        <hr>
                        <h5 class="mb-3">
                            Available Donors for Blood Group: 
                            <span class="badge bg-danger">{{ $bloodGroup }}</span>
                        </h5>
                        
                        @if($donors->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-danger">
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Blood Group</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($donors as $donor)
                                    <tr>
                                        <td>{{ $donor->name }}</td>
                                        <td>
                                            <a href="tel:{{ $donor->mobile }}" class="text-decoration-none">
                                                <i class="bi bi-telephone me-1"></i>{{ $donor->mobile }}
                                            </a>
                                        </td>
                                        <td><span class="badge bg-danger">{{ $donor->blood_group }}</span></td>
                                        <td>
                                            <span class="badge bg-success">Available</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-warning text-center">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            No available donors found for {{ $bloodGroup }} blood group.
                            <br>
                            <small>Please try again later or contact the foundation for emergency assistance.</small>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body text-center">
                        <h5>Need Emergency Blood?</h5>
                        <p class="text-muted">Contact our emergency helpline for immediate assistance.</p>
                        <a href="tel:+966XXXXXXXX" class="btn btn-danger">
                            <i class="bi bi-telephone-fill me-2"></i>Call Emergency
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
