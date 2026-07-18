@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.members.show', $member) }}">{{ $member->name }}</a></li>
<li class="breadcrumb-item active">Documents</li>

@section('page_actions')
@can('documents.create')
<a href="{{ route('admin.documents.create', $member) }}" class="btn btn-primary">
    <i class="bi bi-upload me-2"></i>Upload Document
</a>
@endcan
@endsection

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="rounded-circle mb-3" width="100" height="100">
                <h5>{{ $member->name }}</h5>
                <p class="text-muted">{{ $member->member_id }}</p>
                <a href="{{ route('admin.members.show', $member) }}" class="btn btn-outline-primary btn-sm">
                    View Profile
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Documents</h5>
            </div>
            <div class="card-body">
                @if($documents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Document</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                            <tr>
                                <td>
                                    <strong>{{ $document->title }}</strong>
                                    <br><small class="text-muted">{{ $document->file_size_formatted }}</small>
                                </td>
                                <td><span class="badge bg-secondary">{{ $document->type }}</span></td>
                                <td>
                                    @if($document->is_verified)
                                    <span class="badge bg-success">Verified</span>
                                    @else
                                    <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-folder2-open" style="font-size: 3rem;"></i>
                    <p class="mt-2">No documents uploaded yet</p>
                    @can('documents.create')
                    <a href="{{ route('admin.documents.create', $member) }}" class="btn btn-primary">
                        Upload First Document
                    </a>
                    @endcan
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
