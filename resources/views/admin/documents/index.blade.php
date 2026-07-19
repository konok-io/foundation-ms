@extends('admin.layouts.app')

@section('content')
@section('breadcrumb')
<li class="breadcrumb-item active">Documents</li>
@endsection

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="document_type" class="form-select" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    @foreach(\App\Models\Document::DOCUMENT_TYPES as $key => $type)
                    <option value="{{ $key }}" {{ request('document_type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="is_verified" class="form-select" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="0" {{ request('is_verified') == '0' ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ request('is_verified') == '1' ? 'selected' : '' }}>Verified</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search member..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Document</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Status</th>
                        <th>Uploaded</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                    <tr>
                        <td>
                            <a href="{{ route('admin.members.show', $document->member) }}">
                                {{ $document->member?->name ?? 'N/A' }}
                            </a>
                            <br><small class="text-muted">{{ $document->member?->member_id }}</small>
                        </td>
                        <td>{{ $document->title }}</td>
                        <td><span class="badge bg-secondary">{{ $document->type }}</span></td>
                        <td>{{ $document->file_size_formatted }}</td>
                        <td>
                            @if($document->is_verified)
                            <span class="badge bg-success">Verified</span>
                            @else
                            <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>{{ $document->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.documents.show', $document) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.documents.download', $document) }}" class="btn btn-outline-success"><i class="bi bi-download"></i></a>
                                @can('documents.verify')
                                <a href="{{ route('admin.documents.verify', $document) }}" class="btn btn-outline-{{ $document->is_verified ? 'warning' : 'success' }}">
                                    <i class="bi bi-{{ $document->is_verified ? 'x-circle' : 'check-circle' }}"></i>
                                </a>
                                @endcan
                                @can('documents.delete')
                                <form action="{{ route('admin.documents.destroy', $document) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger delete-btn"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No documents found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $documents->withQueryString()->links() }}
    </div>
</div>
@endsection
