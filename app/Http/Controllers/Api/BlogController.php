<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{

    public function poll(Request $request)
    {

        $blogs = Blog::with('author', 'comments')->get(['id', 'title', 'status', 'created_at', 'created_by']);

        return response()->json($blogs);
    }

    public function blogs(Request $request)
    {

        $status = 'published';
        if (isset($request->status) && !empty($status)) {
            $status = $request->status;
        }

        $query = Blog::where('status', $status);
        if (isset($request->author_id) && !empty($request->author_id)) {
            $query = $query->where('created_by', $request->author_id);
        }
        $blogs = $query->with('author')->get(['id', 'title', 'status', 'created_at', 'created_by']);

        return response()->json($blogs);
    }
}
