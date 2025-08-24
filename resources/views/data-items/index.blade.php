@extends('layouts.app')

@section('title', 'Data Items')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Data Items
                </h5>
                <div class="btn-group" role="group">
                    <a href="{{ route('data-items.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i>Add New
                    </a>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#generateModal">
                        <i class="fas fa-magic me-1"></i>Generate 1000
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#clearModal">
                        <i class="fas fa-trash me-1"></i>Clear All
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($dataItems->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataItems as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ Str::limit($item->title, 50) }}</td>
                                        <td>{{ Str::limit($item->description, 100) }}</td>
                                        <td>
                                            <span class="status-{{ $item->status }}">
                                                {{ $item->status_label }}
                                            </span>
                                        </td>
                                        <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $item->updated_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('data-items.show', $item) }}" class="btn btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('data-items.edit', $item) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('data-items.destroy', $item) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $dataItems->links() }}
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-database fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No data items found</h5>
                        <p class="text-muted">Start by adding some data items or generate 1000 random items.</p>
                        <div class="mt-3">
                            <a href="{{ route('data-items.create') }}" class="btn btn-primary me-2">
                                <i class="fas fa-plus me-1"></i>Add New Item
                            </a>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#generateModal">
                                <i class="fas fa-magic me-1"></i>Generate 1000 Items
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Google Sheets Configuration Card -->
<div class="row mb-4 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fab fa-google me-2"></i>Google Sheets Configuration
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('google-sheets.config') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <input type="hidden" name="key" value="sheet_url">
                                <label for="sheet_url" class="form-label">Google Sheets URL</label>
                                <input type="url" class="form-control" id="sheet_url" name="value"
                                       value="{{ $config ?? '' }}"
                                       placeholder="https://docs.google.com/spreadsheets/d/...">
                                <div class="form-text">
                                    Enter the URL of your Google Sheets document. Make sure it has editing permissions for any user.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Config
                            </button>
                        </div>
                    </div>
                </form>

                @if($config)
                    <div class="mt-3">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Current Configuration:</strong> {{ $config }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Generate Data Modal -->
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-magic me-2"></i>Generate 1000 Data Items
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>This will generate 1000 random data items with an even distribution of status values (Allowed/Prohibited).</p>
                <p><strong>Warning:</strong> This will clear all existing data items first.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('data-items.generate') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-magic me-1"></i>Generate Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Clear Data Modal -->
<div class="modal fade" id="clearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-trash me-2"></i>Clear All Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Warning:</strong> This will permanently delete all data items from the database.</p>
                <p>This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('data-items.clear') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Clear All Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

