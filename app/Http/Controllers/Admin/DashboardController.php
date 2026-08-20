<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBlogs = Blog::count();
        $totalViews = (int) Blog::sum('view_count');
        $totalLikes = (int) Blog::sum('like_count');
        $totalComments = (int) Blog::sum('comment_count');

        $topBlogs = Blog::query()
            ->orderByDesc('view_count')
            ->orderByDesc('id')
            ->get();

        return view('admin.dashboard', compact(
            'totalBlogs',
            'totalViews',
            'totalLikes',
            'totalComments',
            'topBlogs'
        ));
    }
}
