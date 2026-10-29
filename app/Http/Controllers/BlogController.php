<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Blog::where('is_published', true)
            ->with('blogCategory')
            ->latest('published_at')
            ->paginate(9);

        $categories = BlogCategory::withCount(['blogs' => fn ($q) => $q->where('is_published', true)])->get();

        return view('blog.index', compact('posts', 'categories'));
    }

    public function show(Blog $blog)
    {
        abort_unless($blog->is_published, 404);

        return view('blog.show', compact('blog'));
    }
}
