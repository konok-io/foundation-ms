@extends('admin.layouts.app')

@section('content')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.expenses.index') }}">Accounting</a></li>
<li class="breadcrumb-item active">Expense Categories</li>
@endsection

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Expense Categories</h5>
        @can('expense_categories.create')
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-lg me-2"></i>Add Category
        </button>
        @endcan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Name (Bangla)</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td><code>{{ $category->code }}</code></td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->name_bn ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @can('expense_categories.edit')
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="{{ route('admin.expense-categories.toggle', $category) }}" class="btn btn-outline-{{ $category->is_active ? 'warning' : 'success' }}">
                                    <i class="bi bi-power"></i>
                                </a>
                                @endcan
                                @can('expense_categories.delete')
                                <form action="{{ route('admin.expense-categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger delete-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    @can('expense_categories.edit')
                    <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.expense-categories.update', $category) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Category</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name" value="{{ $category->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Name (Bangla)</label>
                                            <input type="text" class="form-control" name="name_bn" value="{{ $category->name_bn }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description" rows="2">{{ $category->description }}</textarea>
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
                        <td colspan="5" class="text-center py-4 text-muted">No categories found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
@can('expense_categories.create')
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.expense-categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Expense Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name (Bangla)</label>
                        <input type="text" class="form-control" name="name_bn">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@endsection
