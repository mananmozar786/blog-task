<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'comment' => 'required|string',
        ]);
        $blog = Blog::findOrFail($request->blog_id);
        if ($blog->status !== 'published') {
            return redirect()->back()->withErrors('Can comment only on published blogs.');
        }
        Comment::create([
            'blog_id' => $blog->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);
        return redirect()->back()->with('success', 'Comment added.');
    }

    public function destroy(Comment $comment)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Admins only.');
        }
        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted.');
    }
}
