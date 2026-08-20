@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
    <div class="page-header">
        <div>
            <h1>Analytics</h1>
            <p class="page-header-sub">Overview of blog performance across BlogSpace.</p>
        </div>
    </div>

    <div class="row g-3 analytics-stats-row">
        <div class="col-xl-3 col-md-6">
            <div class="analytics-stat-card">
                <div class="analytics-stat-icon blogs">
                    <i class="fas fa-blog"></i>
                </div>
                <div>
                    <span class="analytics-stat-label"> Blogs</span>
                    <h3 class="analytics-stat-value">{{ number_format($totalBlogs) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="analytics-stat-card">
                <div class="analytics-stat-icon views">
                    <i class="fas fa-eye"></i>
                </div>
                <div>
                    <span class="analytics-stat-label"> Views</span>
                    <h3 class="analytics-stat-value">{{ number_format($totalViews) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="analytics-stat-card">
                <div class="analytics-stat-icon likes">
                    <i class="fas fa-heart"></i>
                </div>
                <div>
                    <span class="analytics-stat-label"> Likes</span>
                    <h3 class="analytics-stat-value">{{ number_format($totalLikes) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="analytics-stat-card">
                <div class="analytics-stat-icon comments">
                    <i class="fas fa-comments"></i>
                </div>
                <div>
                    <span class="analytics-stat-label"> Comments</span>
                    <h3 class="analytics-stat-value">{{ number_format($totalComments) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card w-100 blog-list-card analytics-table-card mt-4">
        <div class="card-header blog-list-header">
            <h5 class="mb-0">Blogs by Views</h5>
        </div>
        <div class="card-body blog-list-body">
            <div class="table-responsive">
                <table class="table border table-sm table-bordered mb-0 align-middle analytics-table">
                    <thead>
                        <tr>
                            <th class="text-center" width="70">Rank</th>
                            <th>Title</th>
                            <th class="text-center">Views</th>
                            <th class="text-center">Likes</th>
                            <th class="text-center">Comments</th>
                            <th class="text-center">Published</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topBlogs as $index => $blog)
                            <tr>
                                <td class="text-center">
                                    <span class="analytics-rank">{{ $index + 1 }}</span>
                                </td>
                                <td>
                                    <div class="analytics-blog-title">{{ $blog->title }}</div>
                                </td>
                                <td class="text-center">{{ number_format((int) $blog->view_count) }}</td>
                                <td class="text-center">{{ number_format((int) $blog->like_count) }}</td>
                                <td class="text-center">{{ number_format((int) $blog->comment_count) }}</td>
                                <td class="text-center">{{ optional($blog->created_at)->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No blogs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
