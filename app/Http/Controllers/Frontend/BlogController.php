<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $sort = $request->input('sort', 'newest');

        $query = Blog::query()->active();

        if ($search !== '') {
            $query->where(function ($inner) use ($search) {
                $inner->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($sort === 'oldest') {
            $query->orderBy('created_at')->orderBy('id');
        } elseif ($sort === 'az') {
            $query->orderBy('title');
        } else {
            $sort = 'newest';
            $query->orderByDesc('created_at')->orderByDesc('id');
        }

        $blogs = $query->paginate(9)->withQueryString();

        return view('Frontend.Pages.blog', compact('blogs', 'search', 'sort'));
    }

    /**
     * Show a published blog with TOC and related posts.
     */
    public function show($id): View
    {
        $blog = Blog::query()
            ->active()
            ->where(function ($query) use ($id) {
                $query->where('id', $id)->orWhere('slug', $id);
            })
            ->firstOrFail();

        $tocItems = $blog->tableOfContents();
        $blogContent = $blog->contentWithHeadingIds();
        $relatedBlogs = $blog->relatedBlogs(4);

        return view('Frontend.Pages.blog-detail', compact(
            'blog',
            'tocItems',
            'blogContent',
            'relatedBlogs'
        ));
    }
}
