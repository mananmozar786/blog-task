@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-6">
                <h5 class="card-title">Edit Blog</h5>
            </div>
            <div class="col-sm-6" style="text-align: end;">
                <a href="{{ route('blogs.index') }}" class="btn btn-sm btn-primary">Back</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('blogs.update', $blog) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Blog Title</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $blog->title) }}" required maxlength="255">
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea id="content" name="content" rows="5" class="form-control" required>{{ old('content', $blog->content) }}</textarea>
            </div>
            @if($blog->status === 'rejected')
                <div class="alert alert-warning">
                    This blog has been rejected and cannot be edited.
                </div>
            @endif
            <button type="submit"   class="btn btn-primary">Update</button>
            <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection