@extends('layouts.frontend')

@php
    $comments = [
        [
            'author' => 'Sarah Smith',
            'time' => '2 days ago',
            'body' => 'Great article! The explanation is very clear and easy to understand for beginners learning Laravel.',
            'color' => '',
        ],
        [
            'author' => 'Michael Brown',
            'time' => '5 days ago',
            'body' => 'Thanks for sharing this guide. The Table of Contents is especially helpful for navigating through the article.',
            'color' => 'avatar-blue',
        ],
        [
            'author' => 'Emma Wilson',
            'time' => '1 week ago',
            'body' => 'Very useful article. Looking forward to reading more Laravel tutorials and guides.',
            'color' => 'avatar-green',
        ],
    ];

    $blogTitle = $blog->title;
    $blogDescription = $blog->description;
    $blogImage = $blog->imageUrl();
    $blogViews = (int) $blog->view_count;
    $blogLikes = (int) $blog->like_count;
    $blogCommentsCount = (int) $blog->comment_count;
    $blogCategory = $blog->tagList()[0] ?? 'Blog';
    $blogAuthor = 'Admin';
    $blogAuthorRole = 'BlogSpace Writer';
    $blogPublishedAt = $blog->created_at;
    $blogReadTime = max(1, (int) ceil(str_word_count(strip_tags((string) $blog->content)) / 200)) . ' min read';
@endphp

@section('title', ($blog->seo_title ?: $blog->title) . ' | BlogSpace')
@section('meta_description', $blog->seo_description ?: \Illuminate\Support\Str::limit(strip_tags((string) $blog->description), 160))
@section('canonical', $blog->canonical_url ?: url()->current())

@push('head')
    @if ($blog->schema_markup)
        <script type="application/ld+json">{!! json_encode($blog->schema_markup, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
@endpush

@section('content')

    <!-- ========================= BREADCRUMB ========================== -->

    <section class="blog-breadcrumb">
        <div class="container">
            <nav aria-label="Breadcrumb">
                <a href="/">Home</a>
                <i class="bi bi-chevron-right"></i>
                <a href="{{ url('/') }}">Blogs</a>
                <i class="bi bi-chevron-right"></i>
                <span>{{ $blogTitle }}</span>
            </nav>
        </div>
    </section>


    <!-- ========================= BLOG DETAIL ========================== -->

    <section class="blog-detail-section">
        <div class="container">
            <div class="blog-detail-main">


                <!-- BANNER IMAGE -->
                <div class="blog-detail-banner">
                    <img src="{{ $blogImage }}" alt="{{ $blogTitle }}">
                </div>


                <!-- HEADER -->
                <div class="blog-detail-header">

                    <span class="blog-category">
                        <i class="bi bi-bookmark"></i>
                        {{ $blogCategory }}
                    </span>

                    <h1>{{ $blogTitle }}</h1>

                    <p class="blog-detail-intro">{{ $blogDescription }}</p>

                </div>


                <!-- VIEWS / LIKES / COMMENTS -->
                <div class="blog-detail-stats">

                    <div class="detail-stat-list">
                        <span>
                            <i class="bi bi-eye"></i>
                            {{ $blogViews >= 1000 ? number_format($blogViews / 1000, 1) . 'K' : $blogViews }} Views
                        </span>
                        <span>
                            <i class="bi bi-heart"></i>
                            {{ $blogLikes }} Likes
                        </span>
                        <span>
                            <i class="bi bi-chat-dots"></i>
                            {{ $blogCommentsCount }} Comments
                        </span>
                    </div>

                    @auth
                        <button type="button" class="detail-like-btn">
                            <i class="bi bi-heart"></i> Like
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="detail-login-like">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login to Like
                        </a>
                    @endauth

                </div>


                <!-- AUTHOR DETAILS -->
                <div class="author-card">

                    <div class="author-avatar">
                        {{ collect(explode(' ', $blogAuthor))->map(fn($part) => mb_substr($part, 0, 1))->join('') }}
                    </div>

                    <div class="author-info">
                        <span>Published by</span>
                        <h2>{{ $blogAuthor }}</h2>
                        <p>{{ $blogAuthorRole }}</p>
                    </div>

                    <div class="author-meta">
                        <span>
                            <i class="bi bi-calendar3"></i>
                            {{ \Carbon\Carbon::parse($blogPublishedAt)->format('F j, Y') }}
                        </span>
                        <span>
                            <i class="bi bi-clock"></i>
                            {{ $blogReadTime }}
                        </span>
                    </div>

                </div>


                @if (count($tocItems) >= 2)
                <div class="table-of-contents">

                    <div class="toc-title">
                        <i class="bi bi-list-ul"></i>
                        <h2>Table of Contents</h2>
                    </div>

                    <ul>
                        @foreach ($tocItems as $index => $item)
                            <li>
                                <a href="#{{ $item['id'] }}">
                                    <span>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    {{ $item['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                </div>
                @endif

                <article class="blog-content">
                    {!! $blogContent !!}
                </article>


                @if ($relatedBlogs->isNotEmpty())
                <section class="related-blogs-section">

                    <div class="section-heading">
                        <span class="section-label"><span></span> KEEP READING</span>
                        <h2>Related Blogs</h2>
                        <p>You may also like these articles.</p>
                    </div>

                    <div class="row g-4">
                        @foreach ($relatedBlogs as $relatedBlog)
                            <div class="col-md-6">
                                <a href="{{ route('blog-detail', $relatedBlog->slug) }}" class="related-blog-card">

                                    <div class="related-blog-image">
                                        <img src="{{ $relatedBlog->imageUrl() }}" alt="{{ $relatedBlog->title }}">
                                    </div>

                                    <div class="related-blog-content">
                                        <h3>{{ $relatedBlog->title }}</h3>
                                        <p>{{ $relatedBlog->description }}</p>

                                        <div class="related-blog-stats">
                                            <span><i class="bi bi-eye"></i> {{ $relatedBlog->view_count >= 1000 ? number_format($relatedBlog->view_count / 1000, 1) . 'K' : (int) $relatedBlog->view_count }}</span>
                                            <span><i class="bi bi-heart"></i> {{ (int) $relatedBlog->like_count }}</span>
                                            <span><i class="bi bi-chat-dots"></i> {{ (int) $relatedBlog->comment_count }}</span>
                                        </div>

                                        <span class="read-more-link">Read More <i class="bi bi-arrow-right"></i></span>
                                    </div>

                                </a>
                            </div>
                        @endforeach
                    </div>

                </section>
                @endif


                <!-- SEARCH & SORT -->
                <section class="detail-search-section">

                    <div class="detail-search-header">
                        <h2>Explore More Blogs</h2>
                        <p>Search and discover more interesting articles.</p>
                    </div>

                    <form action="{{ url('/') }}" method="GET" role="search">
                        <div class="detail-search-row">

                            <div class="detail-search-box">
                                <i class="bi bi-search"></i>
                                <input type="search" name="search" placeholder="Search blogs..." aria-label="Search blogs">
                            </div>

                            <div class="detail-sort-wrapper">
                                <i class="bi bi-arrow-down-up"></i>
                                <label class="visually-hidden" for="detail-sort">Sort blogs</label>
                                <select id="detail-sort" name="sort">
                                    <option value="newest">Newest First</option>
                                    <option value="oldest">Oldest First</option>
                                    <option value="az">A-Z Order</option>
                                </select>
                            </div>

                        </div>
                    </form>

                </section>


                <!-- COMMENTS -->
                <section class="comments-section">

                    <div class="comments-heading">
                        <span class="section-label"><span></span> DISCUSSION</span>
                        <h2>Comments</h2>
                        <p>{{ $blogCommentsCount }} comments on this article</p>
                    </div>

                    @auth
                        <div class="comment-form-card">
                            <h3>Leave a Comment</h3>
                            <form action="#" method="POST">
                                @csrf
                                <textarea name="comment" rows="5" placeholder="Write your comment..."></textarea>
                                <div class="comment-form-action">
                                    <button type="submit">
                                        Post Comment <i class="bi bi-send"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="login-comment-message">
                            <i class="bi bi-lock"></i>
                            <div>
                                <strong>Want to join the discussion?</strong>
                                <p>Please login to post a comment.</p>
                            </div>
                            <a href="{{ route('login') }}">Login</a>
                        </div>
                    @endauth

                    @foreach ($comments as $comment)
                        <div class="comment-item">

                            <div class="comment-avatar {{ $comment['color'] }}">
                                {{ collect(explode(' ', $comment['author']))->map(fn($part) => mb_substr($part, 0, 1))->join('') }}
                            </div>

                            <div class="comment-content">
                                <div class="comment-top">
                                    <h3>{{ $comment['author'] }}</h3>
                                    <span>{{ $comment['time'] }}</span>
                                </div>
                                <p>{{ $comment['body'] }}</p>
                            </div>

                        </div>
                    @endforeach

                </section>


            </div>
        </div>
    </section>

@endsection
