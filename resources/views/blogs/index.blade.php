@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-6">
                <h5 class="card-title">Blogs</h5>
            </div>
            <div class="col-sm-6" style="text-align: end;">
                @if(auth()->user()->isAuthor())
                    <a href="{{ route('blogs.create') }}" class="btn btn-sm btn-primary">Create Blog</a>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped table-responsive">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Author</th>
                    <th>Status</th>
                    @if(auth()->user()->isAuthor())
                        <th>Actions</th>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <th>Approve/Reject</th>
                    @endif
                </tr>
            </thead>
            <tbody>
            @if(count($blogs) > 0)
                @foreach($blogs as $blog)
                    <tr id="blog-{{ $blog->id }}">
                        <td><a href="{{ route('blogs.show', $blog) }}">{{ $blog->title }}</a></td>
                        <td>{{ \App\Helpers\Helper::truncateText($blog->content) }}</td>
                        <td>{{ $blog->author->name }}</td>
                        <td class="blog-status">{{ ucfirst($blog->status) }}</td>
                        @if(auth()->user()->isAuthor() && $blog->created_by == auth()->id())
                        <td>
                            {{-- @if($blog->status != 'rejected') --}}
                                <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form method="POST" action="{{ route('blogs.destroy', $blog) }}" class="d-inline" onsubmit="return confirm('Delete blog?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            {{-- @else
                                <span class="text-muted">Rejected</span>
                            @endif --}}
                            </td>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <td>
                                <form method="POST" action="{{ route('blogs.approve', $blog) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="published" />
                                    <button class="btn btn-sm btn-success">Approve</button>
                                </form>
                                
                                <form method="POST" action="{{ route('blogs.approve', $blog) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected" />
                                    <button class="btn btn-sm btn-warning">Reject</button>
                                </form>

                                <form method="POST" action="{{ route('blogs.destroy', $blog) }}" class="d-inline" onsubmit="return confirm('Delete blog?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            @else
                <span class="text-danger">No data available in table</span>
            @endif
            </tbody>
        </table>

        {{ $blogs->links('pagination::bootstrap-4') }}
    </div>
</div>

@endsection

@section('scripts')
<script>
    setInterval(function () {
        var blogIds = {!! json_encode($blogs->pluck('id')) !!};

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
                    }
                }); 
            }
        });
    }, 10000);
</script>
@endsection