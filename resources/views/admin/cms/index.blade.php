@extends('admin.layouts.app')

@section('content')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
@endsection

@section('page_actions')
<a href="{{ route('admin.cms.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-2"></i>Add Page
</a>
@endsection

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">CMS Pages</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.cms.index') }}" class="d-flex gap-2">
                    <select name="page_type" class="form-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        @foreach($pageTypes as $key => $type)
                        <option value="{{ $key }}" {{ request('page_type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="cmsTable">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($page->image)
                            <img src="{{ asset('storage/' . $page->image) }}" alt="" class="rounded" width="50" height="50" style="object-fit: cover;">
                            @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="{{ $page->icon ?? 'bi bi-file-text' }} text-muted"></i>
                            </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $page->title }}</strong>
                            @if($page->title_bn)
                            <br><small class="text-muted">{{ $page->title_bn }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ $pageTypes[$page->page_type] ?? 'General' }}
                            </span>
                        </td>
                        <td>{{ $page->position }}</td>
                        <td>
                            <span class="badge bg-{{ $page->status ? 'success' : 'warning' }}">
                                {{ $page->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.cms.show', $page) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.cms.edit', $page) }}" class="btn btn-outline-success" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.cms.destroy', $page) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger delete-btn" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-file-text" style="font-size: 3rem;"></i>
                            <p class="mt-2">No CMS pages found</p>
                            <a href="{{ route('admin.cms.create') }}" class="btn btn-primary btn-sm">Create First Page</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $pages->withQueryString()->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#cmsTable').DataTable({
            "paging": false,
            "searching": false,
            "ordering": true,
            "info": false
        });
    });
</script>
@endpush
