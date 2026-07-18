@extends('admin.layouts.app')

@section('content')
@yield('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
<li class="breadcrumb-item active">{{ $document->title }}</li>

@section('page_actions')
<div class="btn-group">
    <a href="{{ route('admin.documents.download', $document) }}" class="btn btn-success">
        <i class="bi bi-download me-2"></i>Download
    </a>
    @can('documents.verify')
    <a href="{{ route('admin.documents.verify', $document) }}" class="btn btn-{{ $document->is_verified ? 'warning' : 'success' }}">
        <i class="bi bi-{{ $document->is_verified ? 'x-circle' : 'check-circle' }} me-2"></i>
        {{ $document->is_verified ? 'Unverify' : 'Verify' }}
    </a>
    @endcan
    @can('documents.delete')
    <form action="{{ route('admin.documents.destroy', $document) }}" method="POST" class="d-inline">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger delete-btn">
            <i class="bi bi-trash me-2"></i>Delete
        </button>
    </form>
    @endcan
</div>
@endsection

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $document->title }}</h5>
                    @if($document->is_verified)
                    <span class="badge bg-success">Verified</span>
                    @else
                    <span class="badge bg-warning">Pending Verification</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-light" style="width: 30%;">Member</th>
                        <td>
                            <a href="{{ route('admin.members.show', $document->member) }}">
                                {{ $document->member?->name ?? 'N/A' }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">Document Type</th>
                        <td>{{ $document->type }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">File Name</th>
                        <td>{{ $document->file_name }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">File Size</th>
                        <td>{{ $document->file_size_formatted }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">File Type</th>
                        <td>{{ $document->mime_type ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Uploaded By</th>
                        <td>{{ $document->uploader?->name ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Upload Date</th>
                        <td>{{ $document->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @if($document->is_verified)
                    <tr>
                        <th class="bg-light">Verified By</th>
                        <td>{{ $document->verifier?->name ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Verified At</th>
                        <td>{{ $document->verified_at?->format('d M Y H:i') }}</td>
                    </tr>
                    @endif
                    @if($document->notes)
                    <tr>
                        <th class="bg-light">Notes</th>
                        <td>{{ $document->notes }}</td>
                    </tr>
                    @endif
                </table>
                
                @if($document->mime_type && in_array($document->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf']))
                <div class="mt-4">
                    <h6>Preview</h6>
                    @if(in_array($document->mime_type, ['image/jpeg', 'image/png', 'image/gif']))
                    <img src="{{ Storage::url($document->file_path) }}" alt="{{ $document->title }}" class="img-fluid rounded" style="max-height: 400px;">
                    @elseif($document->mime_type === 'application/pdf')
                    <div class="alert alert-info">
                        <i class="bi bi-file-pdf me-2"></i>
                        PDF Preview not available. Click Download to view.
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
