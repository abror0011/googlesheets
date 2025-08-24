@extends('layouts.app')

@section('title', 'View Data Item')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-eye me-2"></i>Data Item #{{ $dataItem->id }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>ID:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $dataItem->id }}
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-3">
                        <strong>Title:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $dataItem->title }}
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-3">
                        <strong>Description:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $dataItem->description }}
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-3">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-md-9">
                        <span class="status-{{ $dataItem->status }}">
                            {{ $dataItem->status_label }}
                        </span>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-3">
                        <strong>Created At:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $dataItem->created_at->format('Y-m-d H:i:s') }}
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-3">
                        <strong>Updated At:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $dataItem->updated_at->format('Y-m-d H:i:s') }}
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('data-items.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to List
                    </a>
                    <div>
                        <a href="{{ route('data-items.edit', $dataItem) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('data-items.destroy', $dataItem) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

