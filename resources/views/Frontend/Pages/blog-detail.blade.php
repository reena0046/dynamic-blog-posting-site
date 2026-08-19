@extends('layouts.frontend')

@php
    $blog = [
        'id' => 1,
        'title' => 'Getting Started with Laravel: A Complete Beginner Guide',
        'description' => 'Learn the fundamentals of Laravel and discover how this powerful PHP framework helps developers build modern, scalable and reliable web applications.',
        'image' => 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=1400&q=80',
        'category' => 'Development',
        'views' => 1200,
        'likes' => 230,
        'comments' => 45,
        'published_at' => '2026-01-15',
        'author' => 'John Doe',
        'author_role' => 'Laravel Developer & Technical Writer',
        'read_time' => '8 min read',
    ];

    $relatedBlogs = [
        [
            'id' => 2,
            'title' => 'The Future of Digital Innovation',
            'description' => 'Explore the latest trends and technologies shaping the digital world.',
            'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80',
            'views' => 980,
            'likes' => 186,
            'comments' => 28,
        ],
        [
            'id' => 6,
            'title' => 'How Artificial Intelligence Is Shaping Our Future',
            'description' => 'Discover technology trends and innovations shaping our digital future.',
            'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80',
            'views' => 3100,
            'likes' => 589,
            'comments' => 91,
        ],
    ];

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

    $tocItems = [
        ['id' => 'introduction', 'title' => 'Introduction to Laravel'],
        ['id' => 'requirements', 'title' => 'Laravel Requirements'],
        ['id' => 'installation', 'title' => 'Installing Laravel'],
        ['id' => 'structure', 'title' => 'Understanding Laravel Structure'],
        ['id' => 'conclusion', 'title' => 'Conclusion'],
    ];
@endphp

@section('title', $blog['title'] . ' | BlogSpace')

@section('content')

    <!-- ========================= BREADCRUMB ========================== -->

    <section class="blog-breadcrumb">
        <div class="container">
            <nav aria-label="Breadcrumb">
                <a href="/">Home</a>
                <i class="bi bi-chevron-right"></i>
                <a href="{{ url('/') }}">Blogs</a>
                <i class="bi bi-chevron-right"></i>
                <span>{{ $blog['title'] }}</span>
            </nav>
        </div>
    </section>


    <!-- ========================= BLOG DETAIL ========================== -->

    <section class="blog-detail-section">
        <div class="container">
            <div class="blog-detail-main">


                <!-- BANNER IMAGE -->
                <div class="blog-detail-banner">
                    <img src="{{ $blog['image'] }}" alt="{{ $blog['title'] }}">
                </div>


                <!-- HEADER -->
                <div class="blog-detail-header">

                    <span class="blog-category">
                        <i class="bi bi-bookmark"></i>
                        {{ $blog['category'] }}
                    </span>

                    <h1>{{ $blog['title'] }}</h1>

                    <p class="blog-detail-intro">{{ $blog['description'] }}</p>

                </div>


                <!-- VIEWS / LIKES / COMMENTS -->
                <div class="blog-detail-stats">

                    <div class="detail-stat-list">
                        <span>
                            <i class="bi bi-eye"></i>
                            {{ $blog['views'] >= 1000 ? number_format($blog['views'] / 1000, 1) . 'K' : $blog['views'] }} Views
                        </span>
                        <span>
                            <i class="bi bi-heart"></i>
                            {{ $blog['likes'] }} Likes
                        </span>
                        <span>
                            <i class="bi bi-chat-dots"></i>
                            {{ $blog['comments'] }} Comments
                        </span>
                    </div>

                    @auth
                        <button type="button" class="detail-like-btn">
                            <i class="bi bi-heart"></i> Like
                        </button>
                    @else
                        <a href="#" class="detail-login-like">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login to Like
                        </a>
                    @endauth

                </div>


                <!-- AUTHOR DETAILS -->
                <div class="author-card">

                    <div class="author-avatar">
                        {{ collect(explode(' ', $blog['author']))->map(fn($part) => mb_substr($part, 0, 1))->join('') }}
                    </div>

                    <div class="author-info">
                        <span>Published by</span>
                        <h2>{{ $blog['author'] }}</h2>
                        <p>{{ $blog['author_role'] }}</p>
                    </div>

                    <div class="author-meta">
                        <span>
                            <i class="bi bi-calendar3"></i>
                            {{ \Carbon\Carbon::parse($blog['published_at'])->format('F j, Y') }}
                        </span>
                        <span>
                            <i class="bi bi-clock"></i>
                            {{ $blog['read_time'] }}
                        </span>
                    </div>

                </div>


                <!-- TABLE OF CONTENTS -->
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


                <!-- FULL BLOG CONTENT -->
                <article class="blog-content">

                    <section id="introduction">
                        <h2>Introduction to Laravel</h2>
                        <p>
                            Laravel is one of the most popular PHP frameworks
                            used for building modern and scalable web applications.
                            It provides developers with an elegant syntax and many
                            powerful features that make development faster and easier.
                        </p>
                        <p>
                            Whether you are building a small website, REST API,
                            e-commerce platform, or a large business application,
                            Laravel provides the tools and structure required to
                            develop reliable applications.
                        </p>
                    </section>

                    <section id="requirements">
                        <h2>Laravel Requirements</h2>
                        <p>
                            Before starting a Laravel project, make sure your
                            development environment contains the required software.
                        </p>
                        <ul>
                            <li>PHP 8.1 or higher</li>
                            <li>Composer</li>
                            <li>MySQL or another supported database</li>
                            <li>Apache or Nginx web server</li>
                        </ul>
                    </section>

                    <section id="installation">
                        <h2>Installing Laravel</h2>
                        <p>
                            Laravel can easily be installed using Composer.
                            Run the following command to create a new project.
                        </p>
                        <div class="code-block">
                            <code>composer create-project laravel/laravel my-project</code>
                        </div>
                        <p>
                            After installation, move into the project directory
                            and start the development server.
                        </p>
                    </section>

                    <section id="structure">
                        <h2>Understanding Laravel Structure</h2>
                        <p>
                            Laravel follows the MVC architecture pattern.
                            The application is organized into different
                            directories that separate business logic,
                            database operations, views, routes and controllers.
                        </p>
                        <p>
                            This structure helps developers maintain clean,
                            reusable and scalable code.
                        </p>
                    </section>

                    <section id="conclusion">
                        <h2>Conclusion</h2>
                        <p>
                            Laravel is an excellent choice for developers
                            who want to build modern PHP applications.
                            Its powerful ecosystem, elegant syntax and
                            built-in features help developers create
                            applications efficiently.
                        </p>
                    </section>

                </article>


                <!-- RELATED BLOGS -->
                <section class="related-blogs-section">

                    <div class="section-heading">
                        <span class="section-label"><span></span> KEEP READING</span>
                        <h2>Related Blogs</h2>
                        <p>You may also like these articles.</p>
                    </div>

                    <div class="row g-4">
                        @foreach ($relatedBlogs as $relatedBlog)
                            <div class="col-md-6">
                                <a href="{{ route('blog-detail', $relatedBlog['id']) }}" class="related-blog-card">

                                    <div class="related-blog-image">
                                        <img src="{{ $relatedBlog['image'] }}" alt="{{ $relatedBlog['title'] }}">
                                    </div>

                                    <div class="related-blog-content">
                                        <h3>{{ $relatedBlog['title'] }}</h3>
                                        <p>{{ $relatedBlog['description'] }}</p>

                                        <div class="related-blog-stats">
                                            <span><i class="bi bi-eye"></i> {{ $relatedBlog['views'] >= 1000 ? number_format($relatedBlog['views'] / 1000, 1) . 'K' : $relatedBlog['views'] }}</span>
                                            <span><i class="bi bi-heart"></i> {{ $relatedBlog['likes'] }}</span>
                                            <span><i class="bi bi-chat-dots"></i> {{ $relatedBlog['comments'] }}</span>
                                        </div>

                                        <span class="read-more-link">Read More <i class="bi bi-arrow-right"></i></span>
                                    </div>

                                </a>
                            </div>
                        @endforeach
                    </div>

                </section>


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
                        <p>{{ $blog['comments'] }} comments on this article</p>
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
                            <a href="#">Login</a>
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
