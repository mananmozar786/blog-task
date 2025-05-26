@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-6">
                <h5 class="card-title">{{ $blog->title }}</h5>        
            </div>
            <div class="col-sm-6" style="text-align: end;">
                <a href="{{ route('blogs.index') }}" class="btn btn-sm btn-primary">Back</a>
            </div>
        </div>
        
    </div>
    <div class="card-body" id="blog-{{ $blog->id }}">
        <p><strong>Author:</strong> {{ $blog->author->name }}</p>
        <p><strong>Status:</strong> <span class="blog-status">{{ ucfirst($blog->status) }}</span></p>

        <div class="border p-3 mb-4" style="white-space: pre-wrap;">{{ $blog->content }}</div>

        <h4>Comments (<span class="blog-comments-counts">{{ $blog->comments->count() }}</span>)</h4>

        <div id="comments-section">
            @foreach($blog->comments as $comment)
            <div class="border p-2 mb-2 rounded">
                <strong>{{ $comment->user->name }}</strong>
                <p>{{ $comment->comment }}</p>
                @if(auth()->check() && auth()->user()->isAdmin())
                <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Delete this comment?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete Comment</button>
                </form>
                @endif
            </div>
            @endforeach
        </div>

        @if(auth()->check())
            <form action="{{ route('comments.store') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                <div class="mb-3">
                    <label for="comment" class="form-label">Add Comment</label>
                    <textarea id="comment" name="comment" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
        @else
            <p><a href="{{ route('login') }}">Login</a> to add comments.</p>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
    setInterval(function () {
        var blogIds = {!! json_encode($blog->pluck('id')) !!};

        $.ajax({
            url: "{{ url('/api/poll') }}",
            type: "GET",
            success: function (data) {
                data.forEach(function (blog) {
                    // alert(blog.id);
                    // alert(blog.status);
                    if (blogIds.includes(blog.id)) {
                        $('#blog-' + blog.id)
                            .find('.blog-status')
                            .text(blog.status.charAt(0).toUpperCase() + blog.status.slice(1));

                        $('#blog-' + blog.id)
                        .find('.blog-comments-counts')
                        .text(blog.comments.length);
                    }
                }); 
            }
        });
    }, 10000); 
</script>
@endsection