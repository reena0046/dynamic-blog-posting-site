<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->where('is_admin', false)
            ->withCount(['comments', 'likes'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        if ($user->is_admin) {
            abort(404);
        }

        $user->loadCount(['comments', 'likes']);

        $activities = $this->blogActivitiesForUser($user);

        return view('admin.users.show', compact('user', 'activities'));
    }

    /**
     * One card per blog with that user's like and/or comments.
     */
    private function blogActivitiesForUser(User $user): LengthAwarePaginator
    {
        $likes = $user->likes()
            ->with('blog:id,title,slug,thumbnail_image')
            ->latest()
            ->get()
            ->unique('blog_id')
            ->keyBy('blog_id');

        $comments = $user->comments()
            ->with('blog:id,title,slug,thumbnail_image')
            ->latest()
            ->get()
            ->unique('id')
            ->groupBy('blog_id');

        $blogIds = $likes->keys()
            ->merge($comments->keys())
            ->unique()
            ->filter()
            ->values();

        $activities = $blogIds->map(function ($blogId) use ($likes, $comments) {
            $like = $likes->get($blogId);
            $blogComments = ($comments->get($blogId) ?? collect())->values();
            $blog = optional($like)->blog ?? optional($blogComments->first())->blog;

            $likedAt = optional($like)->created_at;
            $latestCommentAt = optional($blogComments->first())->created_at;
            $latestAt = collect([$likedAt, $latestCommentAt])->filter()->sortDesc()->first();

            return (object) [
                'blog' => $blog,
                'liked' => (bool) $like,
                'liked_at' => $likedAt,
                'comments' => $blogComments,
                'comments_count' => $blogComments->count(),
                'latest_at' => $latestAt,
            ];
        })
            ->sortByDesc(fn ($item) => optional($item->latest_at)->timestamp ?? 0)
            ->values();

        return $this->paginateCollection($activities, 10);
    }

    private function paginateCollection(Collection $items, int $perPage): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $pageItems = $items->forPage($page, $perPage)->values();

        return (new LengthAwarePaginator(
            $pageItems,
            $items->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        ))->withQueryString();
    }
}
