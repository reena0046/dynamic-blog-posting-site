<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreCommentRequest;
use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::query()
            ->where('status', 'ACTIVE')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return view('Frontend.Pages.blog', compact('blogs'));
    }

    /**
     * Show a published blog with TOC, related posts, and comments.
     */
    public function show(string $slug): View
    {
        $blog = $this->publishedBlog($slug);

        $this->recordView($blog);

        $tocItems = $blog->tableOfContents();
        $blogContent = $blog->contentWithHeadingIds();
        $relatedBlogs = $blog->relatedBlogs(4);
        $comments = $blog->comments()->with('user')->get();
        $isLiked = $blog->isLikedBy(auth()->user());
        $author = $blog->publisher();

        return view('Frontend.Pages.blog-detail', compact(
            'blog',
            'tocItems',
            'blogContent',
            'relatedBlogs',
            'comments',
            'isLiked',
            'author'
        ));
    }

    public function like(string $slug)
    {
        $blog = $this->publishedBlog($slug);
        $userId = auth()->id();

        $existing = $blog->likes()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            $blog->likes()->create(['user_id' => $userId]);
            $liked = true;
        }

        $likeCount = $blog->likes()->count();
        $blog->update(['like_count' => $likeCount]);

        return response()->json([
            'status' => 'success',
            'liked' => $liked,
            'like_count' => $likeCount,
        ]);
    }

    public function storeComment(StoreCommentRequest $request, string $slug): RedirectResponse
    {
        $blog = $this->publishedBlog($slug);

        $blog->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $request->validated('comment'),
        ]);

        $blog->update([
            'comment_count' => $blog->comments()->count(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Your comment has been posted.');
    }

    private function publishedBlog(string $slug): Blog
    {
        return Blog::query()
            ->where('status', 'ACTIVE')
            ->where('slug', $slug)
            ->firstOrFail();
    }

    private function recordView(Blog $blog): void
    {
        $sessionKey = 'viewed_blog_' . $blog->id;

        if (session()->has($sessionKey)) {
            return;
        }

        $blog->increment('view_count');
        session()->put($sessionKey, true);
        $blog->refresh();
    }
}
