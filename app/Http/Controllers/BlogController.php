<?php

namespace App\Http\Controllers;

use App\Exports\BlogsExport;
use App\Imports\BlogsImport;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class BlogController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $blogs = Blog::with('author')->latest()->paginate(10);
        } elseif ($user->isAuthor()) {
            $blogs = Blog::where('created_by', $user->id)->with('author')->latest()->paginate(10);
        } else {
            $blogs = Blog::published()->with('author')->latest()->paginate(10);
        }

        return view('blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->isAuthor()) {
            abort(403, 'Only authors can create blogs.');
        }

        return view('blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAuthor()) {
            abort(403, 'Only authors can create blogs.');
        }

        $request->validate([
            'title' => ['required', 'max:255', Rule::unique('blogs')->where(function ($query) use ($user) {
                return $query->where('created_by', $user->id);
            })],
            'content' => 'required',
        ]);

        Blog::create([
            'title' => $request->title,
            'content' => $request->content,
            'created_by' => $user->id,
        ]);

        return redirect()->route('blogs.index')->with('success', 'Blog created and pending approval.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        $user = Auth::user();

        if (
            $blog->status !== 'published' &&
            (! $user || !$user->isAdmin() && $blog->created_by !== $user->id)
        ) {
            abort(403, 'Blog not visible.');
        }
        $blog->load('author', 'comments.user');

        return view('blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        $user = Auth::user();

        if (!$user->isAuthor() || $blog->created_by !== $user->id) {
            abort(403, 'Unauthorized edit.');
        }

        if ($blog->status === 'rejected') {
            return redirect()->route('blogs.index')->withErrors('Cannot edit rejected blog.');
        }

        return view('blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $user = Auth::user();

        if (!$user->isAuthor() || $blog->created_by !== $user->id) {
            abort(403, 'Unauthorized update.');
        }

        if ($blog->status === 'rejected') {
            return redirect()->route('blogs.index')->withErrors('Cannot update rejected blog.');
        }

        $request->validate([
            'title' => ['required', 'max:255', Rule::unique('blogs')->ignore($blog->id)->where(function ($query) use ($user) {
                return $query->where('created_by', $user->id);
            })],
            'content' => 'required',
        ]);

        $blog->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => 'pending',
        ]);
        return redirect()->route('blogs.index')->with('success', 'Blog updated and pending approval.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $user = Auth::user();

        if ($user->isAdmin() || ($user->isAuthor() && $blog->created_by === $user->id && $blog->status !== 'rejected')) {
            $blog->delete();
            return redirect()->route('blogs.index')->with('success', 'Blog deleted.');
        }

        abort(403, 'Unauthorized delete or rejected blog.');
    }


    public function approve(Request $request, Blog $blog)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Admins only.');
        }

        $request->validate([
            'status' => ['required', Rule::in(['published', 'rejected'])],
        ]);

        $blog->status = $request->status;
        $blog->save();

        return redirect()->back()->with('success', 'Blog status updated.');
    }

    public function export()
    {
        return Excel::download(new BlogsExport, 'blogs.csv');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);
        Excel::import(new BlogsImport, $request->file('csv_file'));
        return redirect()->back()->with('success', 'Blogs imported successfully!');
    }
}
