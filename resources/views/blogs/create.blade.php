@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-6">
                <h5 class="card-title">Create Blog</h5>
            </div>
            <div class="col-sm-6" style="text-align: end;">
                <a href="{{ route('blogs.index') }}" class="btn btn-sm btn-primary">Back</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('blogs.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Blog Title</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required maxlength="255">
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea id="content" name="content" rows="5" class="form-control" required>{{ old('content') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection